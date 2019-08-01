<?php

namespace App\Notifications\TruFit;


use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class WereGladYoureHere extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }


    /**
     * Get the ExpoMessage representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return ExpoMessage
     */
    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->title('Welcome!')
            ->body("Welcome to TruFit Athletic Clubs! Tap to get started!");
    }
}
