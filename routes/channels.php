<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \Modules\ProjectManagement\Models\ProjectConversation::find($conversationId);
    if (!$conversation) {
        return false;
    }
    return $conversation->participants()
        ->where('user_id', $user->id)
        ->where('user_type', get_class($user))
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
