<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->integer('home_elo_before')->nullable()->after('away_score');
            $table->integer('away_elo_before')->nullable()->after('home_elo_before');
            $table->integer('home_elo_after')->nullable()->after('away_elo_before');
            $table->integer('away_elo_after')->nullable()->after('home_elo_after');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            $table->dropColumn(['home_elo_before', 'away_elo_before', 'home_elo_after', 'away_elo_after']);
        });
    }
};
