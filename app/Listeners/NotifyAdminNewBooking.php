<?php

namespace App\Listeners;

use App\Events\BookingPaid;
use App\Notifications\NewBookingNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminNewBooking
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(BookingPaid $event): void
    {
        Notification::send($this->getAdmins(), new NewBookingNotification($event->booking));
    }

    private function getAdmins(): Collection
    {
        return \App\Models\User::role(config('constant.role.admin'))->get();
    }
}