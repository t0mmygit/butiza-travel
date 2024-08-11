<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoreNotificationTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:store-notification-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store notification types to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new \App\Actions\StoreNotificationTypes)();

        $this->newLine();
        $this->line('Notification types stored.'); 
        $this->newLine();

    }
}
