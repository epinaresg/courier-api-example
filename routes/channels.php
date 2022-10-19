<?php

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

/*
Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('private.{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('presence.{groupId}', function ($user, int $groupId) {
    if ($user->canJoinGroup($groupId)) {
        return ['id' => $user->id, 'first_name' => $user->first_name];
    }
});
*/
