<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpecialAllowancesNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $req;
    protected $requestname;

    /**
     * Create a new notification instance.
     */
    public function __construct(Model $req, $requestname)
    {
        $this->req = $req;
        $this->requestname = $requestname;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Special Allowance Request Update')
            ->greeting('Greetings! ')
            ->line("The status of your $this->requestname has been changed.")
            ->line("Status: " . $this->req->status)
            ->line("Release Date: " . ($this->req->releasedate ?? 'Not yet released'))
            ->line("You may log in to your account for further details.")
            ->action('View Details', url('/roleselection'))
            ->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
