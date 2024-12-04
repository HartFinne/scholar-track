<?php

namespace App\Notifications;

use App\Models\applicationforms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalUpdate extends Notification
{
    use Queueable;
    protected $form;

    /**
     * Create a new notification instance.
     */
    public function __construct(applicationforms $form)
    {
        //
        $this->form = $form;
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
            ->subject('Renewal Application Now Open')
            ->greeting('Greetings!')
            ->line("We are pleased to inform you that the renewal application for your scholarship is now open.")
            ->line("**Deadline for Submission**: " . $this->form->endate->format('F j, Y')) // Format the deadline date nicely
            ->line("Please ensure that you submit all the required documents (e.g., report card, utility bill, reflection paper, etc.) before the deadline.")
            ->line("Status: " . $this->form->scholarshipstatus) // Current scholarship status
            ->action('Start Your Renewal', url('/roleselection')) // Link to the renewal page
            ->line('Thank you for your attention, and we look forward to your continued success!');
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
