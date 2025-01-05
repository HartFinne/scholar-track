<?php

namespace App\Notifications;

use App\Models\communityservice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCreate extends Notification implements ShouldQueue
{
    use Queueable;

    protected $workername;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $workername)
    {
        $this->workername = $workername;
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
            ->subject('New Community Service Event')
            ->greeting('Hello ' . $this->workername . '!')
            ->line('This is to inform you that there is a new event')
            ->action('View Event', url('/roleselection'));
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
