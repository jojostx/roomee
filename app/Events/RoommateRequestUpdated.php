<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoommateRequestUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requester_id;
    public $requestedUser_id;
    public $status;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($requester_id, $requestedUser_id, string $status)
    {
        $this->requester_id = $requester_id;
        $this->requestedUser_id = $requestedUser_id;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("request.{$this->requestedUser_id}");
    }
}