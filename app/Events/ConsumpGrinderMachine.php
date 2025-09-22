<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConsumpGrinderMachine implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $machine;
    public $shaft;
    public $shafts_for_grinder;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($shaft,$machine,$shafts_for_grinder)
    { 
        $this->machine = $machine;
        $this->shaft = $shaft;
        $this->shafts_for_grinder = $shafts_for_grinder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('consump-grinder-machine');
    }

    public function broadcastQueue()
    {
        return 'grinder';
    }

    public function broadcastWith()
    {
        return [
            'machine' => $this->machine,
            'shaft' => $this->shaft,
            'shafts_for_grinder' => $this->shafts_for_grinder,

        ];
    }
}
