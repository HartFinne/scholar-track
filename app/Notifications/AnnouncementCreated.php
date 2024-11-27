<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class AnnouncementCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $announcement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        Log::info('Notification delivery channels: ' . $notifiable);
        return ['mail']; // Ensure the notification is sent via mail
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->line('A new announcement has been posted.')
            ->line('Title: ' . $this->announcement->title)
            ->line('Description: ' . $this->announcement->description)
            ->action('View Announcement', url('/roleselection'))
            ->line('Thank you for being part of our platform!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->announcement->title,
            'description' => $this->announcement->description,
        ];
    }
}
