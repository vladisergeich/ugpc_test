<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GrinderWarehouse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shafts;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shafts)
    {
        $this->shafts = $shafts;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('grinder-warehouse');
    }

    public function broadcastQueue()
    {
        return 'grinder_warehouse';
        
    }

    public function broadcastWith()
    {
        return ['shafts' => $this->shafts];
    }
}
