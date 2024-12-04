<?php

namespace App\Notifications;

use App\Models\grades;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GradeUpdate extends Notification
{
    use Queueable;
    protected $grade;
    /**
     * Create a new notification instance.
     */
    public function __construct(grades $grade)
    {
        //
        $this->grade = $grade;
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
            ->subject('Grade Update')
            ->greeting('Greetings! ')
            ->line("The status of your Grade has been changed.")
            ->line("Status: " . $this->grade->GradeStatus)
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
