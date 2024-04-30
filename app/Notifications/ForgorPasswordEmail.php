<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgorPasswordEmail extends Notification
{
    use Queueable;

    protected $user;
    protected $newPassword;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($dataUser, $decryptedPass)
    {
        $this->user = $dataUser;
        $this->newPassword = $decryptedPass;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password')
            ->view(
                'mail.forgotpassword', ['user' => $this->user, 'newPassword' => $this->newPassword]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
