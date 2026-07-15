<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->string('categories')->nullable()->after('description');
        });

        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->string('registered_category')->nullable()->after('team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('categories');
        });

        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->dropColumn('registered_category');
        });
    }
};
