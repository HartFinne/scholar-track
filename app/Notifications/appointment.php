<?php

namespace App\Notifications;

use App\Models\Appointments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class appointment extends Notification implements ShouldQueue
{
    use Queueable;


    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointments $appointment)
    {
        //
        $this->appointment = $appointment;
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
        $status = $this->appointment->status;
        $message = new MailMessage;

        $message->subject('Appointment Status Notification')
            ->greeting('Greetings!');

        if ($status === 'Rejected') {
            $message->line('We regret to inform you that your appointment has been denied.')
                ->line('If you have any questions, please contact one of our social welfare officers for clarification.');
        } elseif ($status === 'Accepted') {
            $message->line('We are pleased to inform you that your appointment has been approved.')
                ->line('Please prepare any necessary documents and be on time for your appointment.');
        } elseif ($status === 'Cancelled') {
            $message->line('We regret to inform you that your appointment has been cancelled due to unforeseen circumstances.')
                ->line('If you have any questions, please contact one of our social welfare officers for clarification.');
        } else {
            $message->line('Your appointment status has been updated. Please log in to your account to view full details.');
        }

        return $message;
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
