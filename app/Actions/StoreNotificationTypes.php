<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoreNotificationTypes
{
    public function __invoke(): void
    {
        $this->storeNotificationTypesToDatabase();
    }

    private function getNotificationTypes(): array
    {
        $notificationsPath = app_path('Notifications');
        $files = File::allFiles($notificationsPath);

        return collect($files)
            ->map(function ($file) {
                /** 
                 *  Get file name without extension
                 * e.g. BookingPaidNotification
                 */
                $className = $file->getFilenameWithoutExtension();
                $fullClassName = config('notification.namespace') . $className;

                if (! class_exists($fullClassName)) {
                    return null;
                }

                /**
                 * Get instance without constructor to avoid ArgumentCountError
                 */
                $instance = $this->getClassInstanceWithoutConstructor($fullClassName);

                if (! method_exists($instance, 'databaseType')) {
                    return null;
                }

                return $instance->databaseType();
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function getClassInstanceWithoutConstructor(string $className): object
    {
        $reflection = new \ReflectionClass($className);

        return $reflection->newInstanceWithoutConstructor(); 
    }

    private function storeNotificationTypesToDatabase(): void
    {
        $types = $this->getNotificationTypes();

        foreach ($types as $type) {
            $name = Str::headline($type);

            DB::table(config('notification.type.table'))->insert([
                'name' => $name,
                'event' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
