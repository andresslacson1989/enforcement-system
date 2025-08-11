<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to this channel.
|
*/

// Public channels don't require an authorization check.
// Broadcast::channel('notifications', function ($user) {
//     return true;
// });

// This is the private channel authorization for your notifications.
// The wildcard '{userId}' in the channel name is automatically
// passed to the callback function.
Broadcast::channel('users.{userId}', function ($user, $user_id) {
  // Check if the authenticated user's ID matches the requested userId.
  // This ensures a user can only listen to their own notifications.
  if ($user->id == $user_id) {
    // You can add more checks here, for example, for a specific role.
    // For example:
    // return $user->hasRole('admin');
    // Or check a specific permission.
    // return $user->can('view notifications');

    return true; // The user is authorized to listen.
  }

  return false; // The user is not authorized.
});
