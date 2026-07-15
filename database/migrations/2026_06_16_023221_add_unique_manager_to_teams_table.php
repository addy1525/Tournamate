<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Safety check: if any manager currently has more than 1 team,
        // we keep only the most recently created team and detach the rest.
        $duplicates = DB::table('teams')
            ->select('manager_id', DB::raw('COUNT(*) as count'))
            ->whereNotNull('manager_id')
            ->groupBy('manager_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            // Keep only the latest team for this manager
            $latestId = DB::table('teams')
                ->where('manager_id', $dup->manager_id)
                ->orderByDesc('created_at')
                ->value('id');

            // Nullify manager_id on older duplicates (do not delete — preserve history)
            DB::table('teams')
                ->where('manager_id', $dup->manager_id)
                ->where('id', '!=', $latestId)
                ->update(['manager_id' => null]);
        }

        // Now safe to add unique constraint
        Schema::table('teams', function (Blueprint $table) {
            $table->unique('manager_id', 'unique_manager_team');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropUnique('unique_manager_team');
        });
    }
};
