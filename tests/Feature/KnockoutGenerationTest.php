<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Pool;
use App\Models\Team;
use App\Models\Fixture;
use App\Models\TournamentRegistration;

class KnockoutGenerationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Helper: create a team with its own unique manager user.
     * Required because of the one-manager-one-team constraint.
     */
    private function createTeamWithManager(string $name): Team
    {
        $manager = User::factory()->create([
            'role'   => 'manager',
            'status' => User::STATUS_ACTIVE,
        ]);

        return Team::create([
            'name'         => $name,
            'manager_name' => 'Test Manager',
            'phone_number' => '0123456789',
            'manager_id'   => $manager->id,
        ]);
    }

    public function test_calculate_standings_and_generate_knockouts()
    {
        // 1. Create admin user
        $admin = User::factory()->create([
            'role'   => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        // 2. Create tournament
        $tournament = Tournament::create([
            'name'            => 'Rugby Test Tournament',
            'venue_name'      => 'National Stadium',
            'venue'           => 'National Stadium',
            'start_date'      => now(),
            'end_date'        => now()->addDays(2),
            'tournament_date' => now(),
            'status'          => 'ongoing',
            'fee'             => 100.00,
        ]);

        // 3. Create 4 pools (Pool A, Pool B, Pool C, Pool D)
        $pools = [];
        foreach (['Pool A', 'Pool B', 'Pool C', 'Pool D'] as $name) {
            $pools[$name] = Pool::create([
                'tournament_id' => $tournament->id,
                'name'          => $name,
            ]);
        }

        // 4. Create teams and assign to pools
        // Each team gets its own unique manager (one-team-per-manager rule)
        $teams = [];
        foreach ($pools as $poolName => $pool) {
            $teams[$poolName] = [];
            for ($i = 1; $i <= 4; $i++) {
                $team = $this->createTeamWithManager("$poolName Team $i");

                TournamentRegistration::create([
                    'tournament_id'  => $tournament->id,
                    'team_id'        => $team->id,
                    'pool_id'        => $pool->id,
                    'manager_id'     => $team->manager_id,
                    'status'         => 'confirmed',
                    'payment_status' => 'paid',
                ]);

                $teams[$poolName][] = $team;
            }
        }

        // 5. Create Pool A fixtures and record scores (MSSM Win=3, Draw=2, Loss=1)
        $poolA  = $pools['Pool A'];
        $teamA1 = $teams['Pool A'][0];
        $teamA2 = $teams['Pool A'][1];
        $teamA3 = $teams['Pool A'][2];
        $teamA4 = $teams['Pool A'][3];

        $fixturesData = [
            [$teamA1, $teamA2, 20, 10], // Team A1 wins
            [$teamA1, $teamA3, 30,  5], // Team A1 wins
            [$teamA1, $teamA4, 40,  0], // Team A1 wins
            [$teamA2, $teamA3, 15, 10], // Team A2 wins
            [$teamA2, $teamA4, 25,  5], // Team A2 wins
            [$teamA3, $teamA4, 10,  5], // Team A3 wins
        ];

        foreach ($fixturesData as $data) {
            Fixture::create([
                'tournament_id' => $tournament->id,
                'pool_id'       => $poolA->id,
                'home_team_id'  => $data[0]->id,
                'away_team_id'  => $data[1]->id,
                'home_score'    => $data[2],
                'away_score'    => $data[3],
                'status'        => 'completed',
                'stage'         => 'Pool Stage',
            ]);
        }

        // Test Pool::calculateStandings()
        $standings = $poolA->calculateStandings();

        $this->assertCount(4, $standings);
        $this->assertEquals($teamA1->id, $standings[0]['team']->id); // Rank 1
        $this->assertEquals(9, $standings[0]['points']);
        $this->assertEquals(3, $standings[0]['won']);

        $this->assertEquals($teamA2->id, $standings[1]['team']->id); // Rank 2
        $this->assertEquals(7, $standings[1]['points']);

        $this->assertEquals($teamA3->id, $standings[2]['team']->id); // Rank 3
        $this->assertEquals(5, $standings[2]['points']);

        $this->assertEquals($teamA4->id, $standings[3]['team']->id); // Rank 4
        $this->assertEquals(3, $standings[3]['points']);

        // Set basic results for Pool B, C, D so rankings can be computed
        foreach (['Pool B', 'Pool C', 'Pool D'] as $pName) {
            $p = $pools[$pName];
            $t = $teams[$pName];
            Fixture::create([
                'tournament_id' => $tournament->id,
                'pool_id'       => $p->id,
                'home_team_id'  => $t[0]->id,
                'away_team_id'  => $t[1]->id,
                'home_score'    => 10,
                'away_score'    => 5,
                'status'        => 'completed',
                'stage'         => 'Pool Stage',
            ]);
        }

        // 6. Test generateKnockouts route via admin login
        $response = $this->actingAs($admin)
            ->post(route('admin.tournaments.generateKnockouts', $tournament->id), [
                'start_datetime' => '2026-06-10 09:00:00',
                'match_duration' => 20,
            ]);

        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('success');

        // Check that 8 knockout fixtures are generated (4 Cup/Plate QFs, 4 Bowl/Shield QFs)
        $knockouts = Fixture::where('tournament_id', $tournament->id)
            ->whereNull('pool_id')
            ->get();

        $this->assertCount(8, $knockouts);

        // Verify Cup/Plate Quarter-Final 1 match structure
        $cupQF1 = Fixture::where('tournament_id', $tournament->id)
            ->where('stage', 'Cup/Plate - Quarter-Final 1')
            ->first();

        $this->assertNotNull($cupQF1);
        $this->assertEquals($teamA1->id, $cupQF1->home_team_id); // A1 (1st Pool A)

        $poolBStandings = $pools['Pool B']->calculateStandings();
        $this->assertEquals($poolBStandings[1]['team']->id, $cupQF1->away_team_id); // B2 (2nd Pool B)

        $this->assertEquals('draft', $cupQF1->status);
    }

    public function test_generate_knockouts_with_two_pools()
    {
        // 1. Create admin user
        $admin = User::factory()->create([
            'role'   => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        // 2. Create tournament
        $tournament = Tournament::create([
            'name'            => '2-Pool Tournament',
            'venue_name'      => 'National Stadium',
            'venue'           => 'National Stadium',
            'start_date'      => now(),
            'end_date'        => now()->addDays(2),
            'tournament_date' => now(),
            'status'          => 'ongoing',
            'fee'             => 100.00,
        ]);

        // 3. Create 2 pools (Pool A, Pool B)
        $pools = [];
        foreach (['Pool A', 'Pool B'] as $name) {
            $pools[$name] = Pool::create([
                'tournament_id' => $tournament->id,
                'name'          => $name,
            ]);
        }

        // 4. Create teams and assign to pools
        // Each team gets its own unique manager (one-team-per-manager rule)
        $teams = [];
        foreach ($pools as $poolName => $pool) {
            $teams[$poolName] = [];
            for ($i = 1; $i <= 4; $i++) {
                $team = $this->createTeamWithManager("$poolName Team $i");

                TournamentRegistration::create([
                    'tournament_id'  => $tournament->id,
                    'team_id'        => $team->id,
                    'pool_id'        => $pool->id,
                    'manager_id'     => $team->manager_id,
                    'status'         => 'confirmed',
                    'payment_status' => 'paid',
                ]);

                $teams[$poolName][] = $team;
            }
        }

        // 5. Set some completed fixtures
        foreach (['Pool A', 'Pool B'] as $pName) {
            $p = $pools[$pName];
            $t = $teams[$pName];
            Fixture::create([
                'tournament_id' => $tournament->id,
                'pool_id'       => $p->id,
                'home_team_id'  => $t[0]->id,
                'away_team_id'  => $t[1]->id,
                'home_score'    => 15,
                'away_score'    => 5,
                'status'        => 'completed',
                'stage'         => 'Pool Stage',
            ]);
        }

        // 6. Test generateKnockouts route for 2 pools
        $response = $this->actingAs($admin)
            ->post(route('admin.tournaments.generateKnockouts', $tournament->id), [
                'start_datetime' => '2026-06-10 14:00:00',
                'match_duration' => 20,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Check that 4 knockout fixtures are generated (2 Cup SF, 2 Bowl SF)
        $knockouts = Fixture::where('tournament_id', $tournament->id)
            ->whereNull('pool_id')
            ->get();

        $this->assertCount(4, $knockouts);

        $cupSF1 = Fixture::where('tournament_id', $tournament->id)
            ->where('stage', 'Cup - Semi-Final 1')
            ->first();

        $this->assertNotNull($cupSF1);
        $this->assertEquals($teams['Pool A'][0]->id, $cupSF1->home_team_id); // A1

        $poolBStandings = $pools['Pool B']->calculateStandings();
        $this->assertEquals($poolBStandings[1]['team']->id, $cupSF1->away_team_id); // B2
    }
}
