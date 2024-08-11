<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingPaidNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Booking $booking
    ) {}

    public function databaseType(): string
    {
        return 'booking_paid';
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Booking Paid',
            'body'  => 'Your payment for booking ID BK-'. $this->booking->id .' has been paid!',
        ];
    }
}