<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('fixture_id')->nullable()->constrained()->onDelete('set null');
            $table->string('field_name')->default('Main Field'); // e.g. "Field A", "Field B"
            $table->string('title')->nullable();                  // e.g. "Field A - Semi Final"
            $table->enum('provider', ['youtube', 'twitch', 'custom'])->default('youtube');
            $table->string('video_id');                           // YouTube video ID atau Twitch channel
            $table->string('stream_url')->nullable();             // Full URL (fallback)
            $table->enum('status', ['live', 'offline', 'scheduled'])->default('offline');
            $table->integer('viewers')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
