<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ElectroPlatingOperations implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $operations;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($operations)
    { 
        $this->operations = $operations;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('electroplating-running-operations');
    }

    public function broadcastQueue()
    {
        return 'electro_plating';
    }

    public function broadcastWith()
    {
        return ['operations' => $this->operations];
    }
}
