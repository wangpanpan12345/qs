<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EditNotify extends Event implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $id)
    {
        //
        $this->user = $user;
        $this->_id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [
            "dailynews.edit.1"
        ];
//        return new PrivateChannel('channel-name');
    }
}
