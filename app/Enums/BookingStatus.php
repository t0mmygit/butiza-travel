<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';

    // Booking that has been paid
    case CONFIRMED = 'confirmed';

    /**
     * Booking that is in progress
     * Tour is ongoing
     */
    case PROGRESS = 'progress';

    /**
     * Booking that has been completed
     * Tour finished
     */
    case COMPLETED = 'completed';

    // Booking that has been cancelled by the user
    case CANCELLED = 'cancelled';

    public function getValue(): string
    {
        return match($this) {
            self::CONFIRMED => 'Confirmed',
            self::PROGRESS  => 'In Progress',
            self::PENDING   => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getSeverity(): string
    {
        return match($this) {
            self::CONFIRMED => 'info',
            self::PROGRESS  => 'secondary',
            self::PENDING   => 'secondary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }
}
