<?php

namespace App\Notifications;

use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class FireExpoPushNote extends Notification
{
    protected $msg, $note_id, $url;
    public function __construct(string $msg, $note_id, string $url = '')
    {
        $this->msg = $msg;
        $this->url = $url;
        $this->note_id = $note_id;
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
            ->setJsonData(['url' => $this->url, 'pushnotes_id' => $this->note_id])
            ->body($this->msg);
    }
}
