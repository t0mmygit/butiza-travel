<?php

namespace App\Notifications;

use App\Models\NotificationMessage;
use App\Models\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\View;

class NewPartnerNotification extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct() 
    {
        // 
    }

    public function databaseType(object $notifiable): string
    {
        return 'new_partner';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $notificationType = NotificationType::where('event', $this->databaseType($notifiable))->first();
        $notification = NotificationMessage::where('notification_type_id', $notificationType->id)->first();

        return [
            'subject' => $notification->subject,
            'message' => $notification->message,
        ];
    }
}
