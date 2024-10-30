<?php

namespace App\Notifications;

use App\Models\lte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LteAnnouncementCreated extends Notification implements ShouldQueue
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
            ->subject('Warning: ' . $this->lte->caseCode)
            ->line('You are ' . $this->lte->violation)
            ->line('Event: ' . $this->lte->eventtype)
            ->line(line: 'Submit an LTE to resolve this')
            ->line('Date Issued: ' . $this->lte->dateissued)
            ->line('Date need to be submitted: ' . $this->lte->deadline)
            ->action('View LTE', url('/login'))
            ->line('Thank you for being part of our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'caseCode' => $this->lte->caseCode,
            'violation' => $this->lte->violation,
            'eventtype' => $this->lte->eventtype,
            'dateissued' => $this->lte->dateissued,
            'deadline' => $this->lte->deadline,
        ];
    }
}
