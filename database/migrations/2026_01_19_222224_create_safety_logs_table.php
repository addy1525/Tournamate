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
        Schema::create('safety_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('temperature', 5, 2)->nullable(); // Temperature in Celsius
            $table->decimal('humidity', 5, 2)->nullable(); // Humidity percentage
            $table->decimal('wind_speed', 5, 2)->nullable(); // Wind speed in km/h
            $table->decimal('wbgt', 5, 2)->nullable(); // Wet Bulb Globe Temperature
            $table->decimal('lightning_risk', 5, 2)->nullable(); // Lightning risk/distance in km
            $table->enum('alert_level', ['safe', 'caution', 'warning', 'danger'])->default('safe');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_logs');
    }
};
