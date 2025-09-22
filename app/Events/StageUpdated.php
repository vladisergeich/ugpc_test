<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StageUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stage;

    public function __construct($stage)
    {
        $this->stage = $stage;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('stage');
    }
    

    public function broadcastWith()
    {
        return ['stage' => $this->stage];
    }
}
