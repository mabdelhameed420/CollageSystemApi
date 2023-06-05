<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $quiz,$questions, $lecture,$classroom;

    /**
     * Create a new event instance.
     */
    public function __construct( $quiz,$questions, $lecture,$classroom)
    {
        $this->quiz = $quiz;
        $this->questions = $questions;
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
            new Channel('quiz-added'),

        ];
    }
    public function broadcastAs(): string
    {
        return 'quiz-added';
    }
}
