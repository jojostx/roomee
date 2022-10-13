<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RoommateRequestUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendRoommateRequestUpdatedNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  RoommateRequestUpdated $event
     * @return void
     */
    public function handle(RoommateRequestUpdated $event)
    {
        Log::info(
            "roommate request updated schema:"
            .route('profile.view', ['user'=> User::find($event->recipient_id)]),
            ['sender_id' => $event->sender_id]
        );

        // if ($event->status == "sent") {
            //  send a mail to the recipient's email and sms to their tel.no with link to the request card
            //  for the sender [route('roommate-requests')#(request_card_{{sender_id}})]
            //  for a new roommate request
        // }

        // else if($event->status = "accepted"){
            // send email & SMS when a person accepts a roommate request
            //  send a mail to the sender's email and sms to their tel.no with link to the profile page
            //  for the recipient 
            // "{{ route('profile.view', ['user'=> User::find($event->recipient_id) ]) }}"
            //  for a new roommate request
        // }
    }
}
