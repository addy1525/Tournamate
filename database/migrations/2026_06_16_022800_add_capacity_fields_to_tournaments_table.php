<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new columns
        Schema::table('tournaments', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_teams')->nullable()->after('fee')
                ->comment('Max number of teams allowed. NULL = unlimited.');
            $table->dateTime('registration_deadline')->nullable()->after('max_teams')
                ->comment('Auto-close registration after this datetime. NULL = always open.');
        });

        // Step 2: Update status enum to include new values
        // MySQL requires manual ALTER for enum changes
        DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('upcoming','ongoing','completed','registration_closed','cancelled') NOT NULL DEFAULT 'upcoming'");
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['max_teams', 'registration_deadline']);
        });

        DB::statement("ALTER TABLE tournaments MODIFY COLUMN status ENUM('upcoming','ongoing','completed') NOT NULL DEFAULT 'upcoming'");
    }
};
