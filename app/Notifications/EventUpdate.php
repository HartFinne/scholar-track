<?php

namespace App\Notifications;

use App\Models\communityservice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(communityservice $event)
    {
        //
        $this->event = $event;
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
            ->subject('Update on Community Service Event')
            ->greeting('Hello ' . $this->event->workername . '!')
            ->line('This is to inform you that the details for the following community service event have been updated:')
            ->line('Event Title: ' . $this->event->title)
            ->line('Event Location: ' . $this->event->eventloc)
            ->line('Event Date: ' . $this->event->eventdate)
            ->line('Meeting Place: ' . $this->event->meetingplace)
            ->line('Call Time: ' . $this->event->calltime)
            ->line('Start Time: ' . $this->event->starttime)
            ->line('Slot Number: ' . $this->event->slotnum)
            ->line('Number of Volunteers: ' . $this->event->volunteersnum)
            ->line('Event Status: ' . $this->event->eventstatus)
            ->line('Thank you for your cooperation.')
            ->line('Sincerely,')
            ->line($this->event->workername)
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
