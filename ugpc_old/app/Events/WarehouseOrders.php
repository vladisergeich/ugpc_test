<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WarehouseOrders implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shaft;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shaft)
    {
        $this->shaft = $shaft;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('warehouse-orders');
    }

    public function broadcastQueue()
    {
        return 'warehouse-orders';
        
    }

    public function broadcastWith()
    {
        return ['shaft' => $this->shaft];
    }
}
