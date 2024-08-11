<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Booking $booking
    ) {}

    public function databaseType(): string
    {
        return 'new_booking';
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'     => 'New Booking',
            'body'      => 'You have a new booking ('. $this->booking->id .')!',
            'icon'      => 'heroicon-o-check-circle',
            'iconColor' => 'success',
            'status'    => 'success',
        ];
    }
}