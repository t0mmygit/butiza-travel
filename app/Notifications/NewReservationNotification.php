<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Reservation $reservation
    ) {}

    public function databaseType(): string
    {
        return 'new_reservation';
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // TODO: Email Service
    }

    public function via(): array
    {
        return ['database'];
    }

    public function toDatabase(): array
    {
        return [
            'title' => 'New Reservation',
            'body'  => 'You have a new reservation ('. $this->reservation->id .').',
            'icon' => null,
            'iconColor' => 'success',
            'status' => 'success',
        ];
    }
}
