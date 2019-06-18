<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewPostSearchChannel
{

    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $data,
            'count' => count($data[1]),
        ]);
    }

}