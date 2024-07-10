<?php

namespace App\Listeners;

use App\Events\NewPartnerRegistered;
use App\Models\User;
use App\Notifications\NewPartnerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewPartnerNotification
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
    public function handle(NewPartnerRegistered $event): void
    {
        $event->user->notify(new NewPartnerNotification($event->user));
    }
}
