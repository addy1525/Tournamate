@extends('layouts.dashboard')

@section('title', 'Tournament Brackets')
@section('page-title', 'Tournament Brackets')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tournament Brackets</h1>
        <p class="page-subtitle">Live knockout stage visualization</p>
    </div>

    <!-- Tournament Selector -->
    <div class="card mb-xl">
        <div class="card-body">
            <div class="grid grid-cols-2" style="gap: var(--spacing-lg);">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tournament</label>
                    <select class="form-select" id="tournament-select">
                        <option value="">Select Tournament</option>
                        @foreach($tournaments as $t)
                            <option value="{{ $t->id }}" {{ isset($tournament) && $tournament->id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Stage</label>
                    <select class="form-select" disabled>
                        <option>Knockout Stage</option>
                        <option>Quarter Finals</option>
                        <option>Semi Finals</option>
                        <option>Final</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

                @if(!isset($tournament) || $tournament->fixtures->where('stage', '!=', 'Pool Stage')->isEmpty())
    <!-- Empty State -->
    <div class="card">
        <div class="card-body" style="padding: var(--spacing-3xl); text-align: center;">
            <div style="max-width: 600px; margin: 0 auto;">
                <i class="fas fa-sitemap fa-4x"
                    style="color: var(--color-text-muted); opacity: 0.3; margin-bottom: var(--spacing-xl);"></i>

                <h2
                    style="font-size: var(--font-size-2xl); font-weight: 700; color: var(--color-text-primary); margin-bottom: var(--spacing-md);">
                    Knockout Brackets Not Generated
                </h2>

                <p
                    style="font-size: var(--font-size-md); color: var(--color-text-secondary); margin-bottom: var(--spacing-xl); line-height: 1.6;">
                    Tournament brackets will appear here once the Admin completes the Pool Stage and generates the Knockout formats.
                </p>
                <div
                    style="padding: var(--spacing-lg); background: var(--color-bg-tertiary); border-radius: 8px; border-left: 3px solid var(--color-electric-blue);">
                    <p style="font-size: var(--font-size-sm); color: var(--color-text-secondary); margin: 0;">
                        <i class="fas fa-info-circle" style="color: var(--color-electric-blue);"></i>
                        Only matches in the "Cup", "Plate", "Bowl", or "Shield" stage will appear here.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @else
    
    <div class="card" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: var(--color-bg-tertiary); border-bottom: 1px solid var(--color-border); padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <h2 class="card-title" style="font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-sitemap text-warning"></i>
                Knockout Stage Bracket
            </h2>
            <div style="display: flex; align-items: center; gap: 10px;">
                <ul class="nav nav-pills" id="bracket-pills" role="tablist" style="border: 1px solid var(--color-border); gap: 0.25rem; background: rgba(0,0,0,0.2); padding: 4px; border-radius: 8px;">
                    <li class="nav-item">
                        <a class="nav-link active py-1 px-3" id="cup-plate-tab" data-toggle="pill" href="#bracket-cup-plate" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🏆 Cup & Plate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1 px-3" id="bowl-shield-tab" data-toggle="pill" href="#bracket-bowl-shield" role="tab" style="font-size: 0.75rem; border-radius: 6px; color: #fff; font-weight: 600;">🛡️ Bowl & Shield</a>
                    </li>
                </ul>
                <button class="btn btn-sm btn-primary" onclick="location.reload()" style="font-size: 0.75rem; padding: 6px 12px; border-radius: 6px;">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body" style="overflow-x: auto; padding: 1.5rem;">
             @php
                $knockoutFixtures = $tournament->fixtures->where('stage', '!=', 'Pool Stage')->where('status', '!=', 'draft');
                
                $findMatch = function($stageName) use ($knockoutFixtures) {
                    return $knockoutFixtures->first(function($f) use ($stageName) {
                        return strtolower($f->stage) === strtolower($stageName);
                    });
                };
             @endphp

             <div class="tab-content" id="bracket-tab-contents" style="margin-top: 0 !important;">
                 <!-- Cup & Plate Bracket Pane -->
                 <div class="tab-pane active" id="bracket-cup-plate" role="tabpanel">
                     @php
                         $qp1 = $findMatch('Cup/Plate - Quarter-Final 1');
                         $qp2 = $findMatch('Cup/Plate - Quarter-Final 2');
                         $qp3 = $findMatch('Cup/Plate - Quarter-Final 3');
                         $qp4 = $findMatch('Cup/Plate - Quarter-Final 4');

                         $cupSf1 = $findMatch('Cup - Semi-Final 1');
                         $cupSf2 = $findMatch('Cup - Semi-Final 2');
                         $plateSf1 = $findMatch('Plate - Semi-Final 1');
                         $plateSf2 = $findMatch('Plate - Semi-Final 2');

                         $cupFinal = $findMatch('Cup - Final');
                         $plateFinal = $findMatch('Plate - Final');
                     @endphp

                      <div class="bracket-container">
                          <!-- Column 1: Quarter-Finals -->
                          <div class="bracket-column">
                              <div class="bracket-column-title">Quarter-Finals</div>
                              @foreach([['name' => 'Cup/Plate QF 1', 'm' => $qp1], ['name' => 'Cup/Plate QF 2', 'm' => $qp2], ['name' => 'Cup/Plate QF 3', 'm' => $qp3], ['name' => 'Cup/Plate QF 4', 'm' => $qp4]] as $qf)
                                  @include('admin.tournaments.partials.bracket-match-node', [
                                      'title' => $qf['name'], 
                                      'match' => $qf['m'],
                                      'info' => 'WINNER ➔ CUP SF | LOSER ➔ PLATE SF'
                                  ])
                              @endforeach
                          </div>

                          <!-- Column 2: Semi-Finals -->
                          <div class="bracket-column">
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🏆 Cup Semi-Finals</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 1', 'match' => $cupSf1, 'info' => 'WINNER ➔ CUP FINAL'])
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup SF 2', 'match' => $cupSf2, 'info' => 'WINNER ➔ CUP FINAL'])
                              </div>
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🥈 Plate Semi-Finals</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 1', 'match' => $plateSf1, 'info' => 'WINNER ➔ PLATE FINAL'])
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate SF 2', 'match' => $plateSf2, 'info' => 'WINNER ➔ PLATE FINAL'])
                              </div>
                          </div>

                          <!-- Column 3: Finals -->
                          <div class="bracket-column">
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🏆 Cup Final</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Cup Final', 'match' => $cupFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                              </div>
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🥈 Plate Final</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Plate Final', 'match' => $plateFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Bowl & Shield Bracket Pane -->
                  <div class="tab-pane" id="bracket-bowl-shield" role="tabpanel">
                      @php
                          $bsSf1 = $findMatch('Bowl/Shield - Semi-Final 1');
                          $bsSf2 = $findMatch('Bowl/Shield - Semi-Final 2');

                          $bowlFinal = $findMatch('Bowl - Final');
                          $shieldFinal = $findMatch('Shield - Final');
                      @endphp

                      <div class="bracket-container" style="justify-content: flex-start; gap: 4rem;">
                          <!-- Column 1: Semi-Finals -->
                          <div class="bracket-column">
                              <div class="bracket-column-title">Semi-Finals</div>
                              @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 1', 'match' => $bsSf1, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                              @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl/Shield SF 2', 'match' => $bsSf2, 'info' => 'WINNER ➔ BOWL FINAL | LOSER ➔ SHIELD FINAL'])
                          </div>

                          <!-- Column 2: Finals -->
                          <div class="bracket-column">
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🥉 Bowl Final</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Bowl Final', 'match' => $bowlFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                              </div>
                              <div class="bracket-sub-section">
                                  <div class="bracket-sub-section-title">🛡️ Shield Final</div>
                                  @include('admin.tournaments.partials.bracket-match-node', ['title' => 'Shield Final', 'match' => $shieldFinal, 'info' => 'CHAMPIONSHIP MATCH'])
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
        </div>
    </div>
    @endif

@endsection

@push('styles')
    <style>
        /* Premium Visual Bracket Styles */
        .bracket-container {
            display: flex;
            gap: 2.5rem;
            align-items: stretch;
            justify-content: space-between;
            overflow-x: auto;
            padding: 1.5rem 0.5rem;
        }
        .bracket-column {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            min-width: 240px;
            flex: 1;
            gap: 1.5rem;
            position: relative;
        }
        .bracket-match-node {
            background: var(--color-bg-primary);
            border: 1px solid var(--color-border);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .bracket-match-node:hover {
            border-color: var(--color-rugby-green-light);
            box-shadow: var(--shadow-glow-green);
            transform: translateY(-2px);
        }
        .bracket-match-header {
            background: rgba(255, 255, 255, 0.03);
            padding: 6px 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--color-text-muted);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .bracket-match-team {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--color-text-secondary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        .bracket-match-team:last-child {
            border-bottom: none;
        }
        .bracket-match-team.winner {
            background: linear-gradient(90deg, rgba(0, 168, 107, 0.15), transparent);
            border-left: 3px solid var(--color-rugby-green);
            color: white;
            font-weight: 600;
        }
        .bracket-match-team.winner .score {
            color: var(--color-rugby-green-light);
            font-weight: 700;
        }
        .bracket-match-team .score {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--color-text-muted);
        }
        .bracket-column-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-text-secondary);
            margin-bottom: 0.75rem;
            text-align: center;
            letter-spacing: 0.075em;
            background: rgba(255, 255, 255, 0.03);
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--color-border);
        }
        .bracket-sub-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
            justify-content: center;
            padding: 0.5rem 0;
        }
        .bracket-sub-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-text-muted);
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
<script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tournament select synchronizer
        const selectEl = document.getElementById('tournament-select');
        if (selectEl) {
            selectEl.addEventListener('change', function() {
                const id = this.value;
                if (id) {
                    window.location.href = `{{ route('shared.brackets') }}?tournament_id=${id}`;
                } else {
                    window.location.href = `{{ route('shared.brackets') }}`;
                }
            });
        }

        const pusherAppKey = "{{ env('PUSHER_APP_KEY', '96f393e214601452f8c3') }}";
        const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";
        
        if (pusherAppKey) {
            const pusher = new Pusher(pusherAppKey, {
                cluster: pusherCluster,
                forceTLS: true
            });

            const channel = pusher.subscribe('live-matches');
            channel.bind('score-updated', function(data) {
                console.log('Match update detected on Brackets page. Reloading bracket tree...');
                location.reload();
            });
        }
    });
</script>
@endpush