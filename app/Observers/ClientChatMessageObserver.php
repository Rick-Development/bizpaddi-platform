<?php

namespace App\Observers;

use Modules\ClientPortal\Models\ChatMessage;
use App\Notifications\NewMessageNotification;
use Modules\ProjectManagement\Models\ProjectMessage;

class ClientChatMessageObserver
{
    /**
     * Handle the ChatMessage "created" event.
     */
    public function created(ChatMessage $message)
    {
        $chat = $message->chat;
        if (!$chat) return;

        // If it's a client chat, the chat has 'client_id' and 'admin_id'
        // If sender is client, notify admin. If sender is admin, notify client.

        $client = \Modules\ClientPortal\Models\Client::find($chat->client_id);
        $admin = \App\Models\Admin::find($chat->admin_id);

        if ($message->sender_type === 'user' || $message->sender_type === 'Modules\ClientPortal\Models\Client') {
            // Sent by client, notify admin
            if ($admin) {
                // Wrap in a pseudo ProjectMessage to reuse the notification
                $fakeProjectMessage = new ProjectMessage();
                $fakeProjectMessage->id = $message->id;
                $fakeProjectMessage->content = $message->message;
                $fakeProjectMessage->sender = $client;
                
                // You might need a custom notification for Client Chats instead, but we can reuse this if we set properties manually.
                // Actually, let's create a ClientChatMessageNotification quickly to be safe and clean.
                $admin->notify(new \App\Notifications\ClientChatMessageNotification($message));
            }
        } else {
            // Sent by admin, notify client
            if ($client) {
                $client->notify(new \App\Notifications\ClientChatMessageNotification($message));
            }
        }
    }
}
