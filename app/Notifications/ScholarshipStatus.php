<?php

namespace App\Notifications;

use App\Models\scholarshipinfo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScholarshipStatus extends Notification implements ShouldQueue
{
    use Queueable;
    protected $scholar;

    /**
     * Create a new notification instance.
     */
    public function __construct(scholarshipinfo $scholar)
    {
        $this->scholar = $scholar;
        //
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
            ->subject('Scholarship Status Update')
            ->greeting('Greetings! ')
            ->line("The status of your Scholarship has been changed.")
            ->line("Status: " . $this->scholar->scholarshipstatus)
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
