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
            // Rename venue to venue_name if needed, or just add venue_name and copy data? 
            // Since it's a dev env, I'll just change the column or add alias. 
            // Let's stick to user request fields.
            
            if (!Schema::hasColumn('tournaments', 'venue_name')) {
                $table->string('venue_name')->nullable()->after('venue');
            }
            if (!Schema::hasColumn('tournaments', 'location_coordinates')) {
                $table->string('location_coordinates')->nullable()->after('venue_name');
            }
            if (!Schema::hasColumn('tournaments', 'start_date')) {
                $table->date('start_date')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tournaments', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('tournaments', 'status')) {
                $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming')->after('end_date');
            }
            
            // Drop old columns if they exist and are replaced
            // $table->dropColumn('tournament_date'); // Let's keep it for now or drop? safer not to drop if not sure.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['venue_name', 'location_coordinates', 'start_date', 'end_date', 'status']);
        });
    }
};
