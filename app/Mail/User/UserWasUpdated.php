<?php

namespace AnchorCMS\Mail\User;

use AnchorCMS\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWasUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected $mail_to_user, $user_who_made_update;

    protected $data;

    /**
     * Create a new message instance.
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $args = $this->data;

        return $this->from(env('MAIL_FROM_ADDRESS','automailer@mail.capeandbay.com'), env('MAIL_FROM_NAME'))
            ->subject('We Detected An Update to your AnchorCMS Account!')
            ->view('emails.users.updated-notification', $args);
    }
}
