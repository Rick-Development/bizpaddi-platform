<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && !str_contains(get_class($user), 'Admin');
});

Broadcast::channel('admin.messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && str_contains(get_class($user), 'Admin');
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \Modules\ProjectManagement\Models\ProjectConversation::find($conversationId);
    if (!$conversation) {
        return false;
    }
    $userClass = get_class($user);
    return $conversation->participants()
        ->where('user_id', $user->id)
        ->where(function ($q) use ($userClass) {
            $q->where('user_type', $userClass)
              ->orWhere('user_type', str_contains($userClass, 'Admin') ? 'Admin' : 'User')
              ->orWhere('user_type', 'LIKE', '%' . class_basename($userClass));
        })
        ->exists();
});


Broadcast::channel('task-comments', function ($user) {
    return $user !== null;
});

Broadcast::channel('online', function ($user) {
    if ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'type' => get_class($user),
            'key' => get_class($user) . '_' . $user->id,
        ];
    }
    return false;
});
