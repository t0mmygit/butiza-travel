<?php

namespace App\Listeners;

use App\Events\BookingPaid;
use App\Models\Booking;
use App\Notifications\BookingPaidNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyCustomerBookingPaid
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingPaid $event): void
    {
        $this->isUserAuthenticatedOrHasAccount($event->booking)
            ? Notification::send($event->booking->user, new BookingPaidNotification($event->booking)) 
            : $this->sendUserEmailOnly($event->booking);
    }

    private function isUserAuthenticatedOrHasAccount(Booking $booking): bool
    {
        return auth()->check() || $booking->user;
    }

    /**
     * Handle user who is not authenticated or does not an account
     */
    private function sendUserEmailOnly(Booking $booking): void
    {
        // TODO: Either use Notifcation Mail or Mail Service
        // THIS DOES NOT WORK
        Notification::route('mail', $booking->email)->notify(new BookingPaidNotification($booking)); 
    }
}
