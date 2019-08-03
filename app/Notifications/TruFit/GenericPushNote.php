<?php

namespace App\Notifications\TruFit;

use App\MessagingTemplates;
use Illuminate\Notifications\Notification;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class GenericPushNote extends Notification
{
    protected $template;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($template)
    {
        $this->template = MessagingTemplates::find($template['id']);
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
            ->title($this->template->name)
            ->body($this->template->renderMessage($this->template->message));
    }
}
