<?php

namespace App\Events;

use App\Enums\BlockStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserBlocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string|int
     */
    public $blocker_id;
       
    /**
     * @var string|int
     */
    public $blockedUser_id;
       
    /**
     * @var App\Enums\BlockStatus
     */
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($blocker_id, $blockedUser_id, BlockStatus $status)
    {
        $this->blocker_id = $blocker_id;
        $this->blockedUser_id = $blockedUser_id;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("blocking.{$this->blockedUser_id}");
    }
}
