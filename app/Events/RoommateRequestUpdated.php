<?php

namespace App\Events;

use App\Enums\RoommateRequestStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoommateRequestUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string|int
     */
    public $sender_id;
       
    /**
     * @var string|int
     */
    public $recipient_id;
       
    /**
     * @var App\Enums\RoommateRequestStatus
     */
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender_id, $recipient_id, RoommateRequestStatus $status)
    {
        $this->sender_id = $sender_id;
        $this->recipient_id = $recipient_id;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("roommate-request.{$this->recipient_id}");
    }
}
