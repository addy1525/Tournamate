<?php

namespace App\Events;

use App\Models\Fixture;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fixture;

    /**
     * Create a new event instance.
     */
    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture->load([
            'homeTeam',
            'awayTeam',
            'tournament',
            'pool',
            'matchEvents' => function($q) {
                $q->orderBy('minute', 'asc')->orderBy('created_at', 'asc');
            },
            'matchEvents.team'
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('live-matches');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'score-updated';
    }
}
