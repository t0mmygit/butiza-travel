<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserReservationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Reservation $reservation
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return is_null($notifiable->user) ? ['mail'] : ['database', 'mail'];
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'New Reservation',
            'body' => 'New Reservation has been created',
        ];
    }
}
