<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL does not allow dropping a unique index that is referenced by a FK.
        // We must temporarily drop & re-add the FK around the index change.
        Schema::table('tournament_registrations', function (Blueprint $table) {
            // 1. Drop FK that references tournament_id (which depends on the unique index)
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['team_id']);

            // 2. Drop the old unique constraint
            $table->dropUnique(['tournament_id', 'team_id']);

            // 3. Re-add FKs
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');

            // 4. Add new unique: same team CAN register in different categories
            $table->unique(['tournament_id', 'team_id', 'registered_category'], 'unique_team_category_per_tournament');
        });
    }

    public function down(): void
    {
        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
            $table->dropForeign(['team_id']);
            $table->dropUnique('unique_team_category_per_tournament');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unique(['tournament_id', 'team_id']);
        });
    }
};
