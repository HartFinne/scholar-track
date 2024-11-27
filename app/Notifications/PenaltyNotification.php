<?php

namespace App\Notifications;

use App\Models\penalty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenaltyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $penalty;

    /**
     * Create a new notification instance.
     */


    public function __construct(penalty $penalty)
    {
        //
        $this->penalty = $penalty;
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
            ->subject('Penalty Notification')
            ->line('We regret to inform you that a penalty has been issued against your account.')
            ->line('Violation: ' . $this->penalty->condition)
            ->line('Details: ' . $this->penalty->remark)
            ->line('Date Issued: ' . $this->penalty->dateofpenalty->format('Y-m-d'))
            ->action('Review Penalty Details', url('/roleselection'))
            ->line('If you have any questions or need further clarification, please don’t hesitate to contact us.')
            ->line('Thank you for your attention to this matter.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'caseCode' => $this->penalty->caseCode,
            'condition' => $this->penalty->condition,
            'remark' => $this->penalty->remark,
            'dateofpenalty' => $this->penalty->dateofpenalty,
        ];
    }
}
