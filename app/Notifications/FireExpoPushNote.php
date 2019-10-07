<?php

namespace App\Notifications;

use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class FireExpoPushNote extends Notification
{
    protected $msg;
    public function __construct(string $msg)
    {
        $this->msg = $msg;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {
        $hello = $notifiable;
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title('Welcome!')
            ->body($this->msg);
    }
}
