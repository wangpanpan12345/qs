<?php

namespace App\Events;
use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DailyNewsUpdate extends Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $static;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($static)
    {
        //
        $this->static = $static;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            "dailynew.static.1"
        ];
//        return new PrivateChannel('channel-name');
    }
}
