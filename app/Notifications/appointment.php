<?php

namespace App\Notifications;

use App\Models\Appointments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class appointment extends Notification
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
            $message->line('We regret to inform you that your appointment has been ' . $status . '.')
                ->line('If you believe this was a mistake, you may contact our support team for further clarification.');
        } elseif ($status === 'Accepted') {
            $message->line('We are pleased to inform you that your appointment has been ' . $status . '.')
                ->line('Please prepare any necessary documents and be on time for your appointment.');
        } elseif ($status === 'Cancelled') {
            $message->line('We are pleased to inform you that your appointment has been ' . $status . '.')
                ->line('Please prepare any necessary documents and be on time for your appointment.');
        } else {
            $message->line('Your appointment has been ' . $status . '.');
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
