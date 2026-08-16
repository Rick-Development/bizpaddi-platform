<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $messageData = $notification->toFcm($notifiable);
        
        // Get all FCM tokens for this user
        $tokens = $notifiable->fcmTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            return;
        }

        try {
            $factory = (new Factory)
                ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-auth.json')));
            
            $messaging = $factory->createMessaging();

            $fcmNotification = FcmNotification::create($messageData['title'], $messageData['body']);

            $message = CloudMessage::new()
                ->withNotification($fcmNotification)
                ->withData($messageData['data'] ?? []);

            $report = $messaging->sendMulticast($message, $tokens);

            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    Log::warning('FCM Notification failed: ' . $failure->error()->getMessage());
                    // Optionally, remove invalid tokens:
                    // if ($failure->error()->getMessage() === 'Requested entity was not found.') {
                    //    FcmToken::where('token', $failure->target()->value())->delete();
                    // }
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
        }
    }
}
