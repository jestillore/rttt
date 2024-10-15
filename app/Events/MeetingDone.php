<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingDone implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $meeting,
        public int $audience
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel($this->meeting),
        ];
    }

    public function broadcastAs(): string
    {
        return 'audience.' . $this->audience . '.done';
    }
}
