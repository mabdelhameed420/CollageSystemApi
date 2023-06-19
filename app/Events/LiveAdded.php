<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $users, $lecture, $classroom;

    /**
     * Create a new event instance.
     */
    public function __construct( $users, $lecture, $classroom)
    {
        $this->users = $users;
        $this->lecture = $lecture;
        $this->classroom = $classroom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('live-added'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'live-added';
    }

}
