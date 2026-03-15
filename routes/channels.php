<?php

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('roommate-request.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('blocking.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-room.{chatRoomId}', function (User $user, string $chatRoomId) {
    $room = ChatRoom::find($chatRoomId);

    if (!$room) {
        return false;
    }

    return (int) $room->user_a_id === $user->getKey()
        || (int) $room->user_b_id === $user->getKey();
});