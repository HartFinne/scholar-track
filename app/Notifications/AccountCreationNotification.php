<?php

namespace App\Notifications;

use App\Models\staccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AccountCreationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $staccount;
    protected $password;
    /**
     * Create a new notification instance.
     */
    public function __construct(staccount $staccount, $password)
    {
        //
        $this->staccount = $staccount;
        $this->password = $password;
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
            ->subject('Account Created Successfully')
            ->greeting('Hello ' . $this->staccount->name . '!')
            ->line('Your account has been successfully created with the following details:')
            ->line('**Email**: ' . $this->staccount->email)
            ->line('**Role**: ' . $this->staccount->role)
            ->line('**Area**: ' . $this->staccount->area)
            ->line('**Password**: ' . $this->password)
            ->line('Please log in and change your password for security.')
            ->action('Login to Your Account', url('/roleselection'))
            ->line('Thank you for being part of our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->staccount->name,
            'email' => $this->staccount->email,
            'mmobileno' => $this->staccount->mmobileno,
            'area' => $this->staccount->area,
            'role' => $this->staccount->role,
            'password' => $this->staccount->password,
        ];
    }
}
