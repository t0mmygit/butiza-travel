<?php

namespace App\Enums;

enum BookingStatus: string
{
    case CONFIRMED = 'confirmed';

    case IN_PROGRESS = 'in progress';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';

    public function getValue(): string
    {
        return match($this) {
            self::CONFIRMED => 'Confirmed',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getSeverity(): string
    {
        return match($this) {
            self::CONFIRMED => 'info',
            self::IN_PROGRESS => 'secondary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
