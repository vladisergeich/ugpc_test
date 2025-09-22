<?php

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

Broadcast::channel('grinder-warehouse', function ($user) {
    return (bool) $user;
});

Broadcast::channel('electroplating-running-operations', function ($user) {
    return true;
});

Broadcast::channel('operation_event', function ($user) {
    return true;
});

Broadcast::channel('consump-grinder-machine', function ($user) {
    return (bool) $user;
});

Broadcast::channel('input-control-status-update', function ($user) {
    return (bool) $user;
});

Broadcast::channel('warehouse-orders', function ($user) {
    return true;
});

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
