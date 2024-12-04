<?php

namespace App\Notifications;

use App\Models\lte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FailedGWA extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lte;

    /**
     * Create a new notification instance.
     */
    public function __construct(lte $lte)
    {
        //
        $this->lte = $lte;
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
            ->subject('Notice to Explain')
            ->greeting('Greetings!')
            ->line('You have been marked as ' . $this->lte->violation)
            ->line('In connection with this, you are advised to submit your written explanation letter within three (3) days of receipt of this notice.')
            ->line('Sincerely,')
            ->line($this->lte->workername)
            ->line('Social Welfare Officer');
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
