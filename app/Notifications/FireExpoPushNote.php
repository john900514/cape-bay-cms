<?php

namespace App\Notifications;

use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class FireExpoPushNote extends Notification
{
    protected $msg, $url;
    public function __construct(string $msg, string $url = '')
    {
        $this->msg = $msg;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title('TruFit Announcement!')
            ->setJsonData(['url' => $this->url])
            ->body($this->msg);
    }
}
