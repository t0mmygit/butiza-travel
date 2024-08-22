<?php

namespace App\Enums;

enum ReservationStatus: string
{
    // Reservation is paid by user
    case CONFIRMED = 'confirmed';

    // Reservation is pending, awaiting payment
    case PENDING = 'pending';
 
    // Reservation is cancelled by the user
    case CANCELLED = 'cancelled';

    // Reservation is rejected by the admin
    case REJECTED = 'rejected';

    public function values(): array
    {
        return [
            'status'   => $this->value,
            'label'    => $this->getLabel(),
            'severity' => $this->getSeverity(),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::CONFIRMED => 'Confirmed',
            self::PENDING   => 'Pending',
            self::CANCELLED => 'Cancelled',
            self::REJECTED  => 'Rejected',
        };
    }

    public function getSeverity(): string
    {
        return match ($this) {
            self::CONFIRMED => config('constant.toast.success'),
            self::PENDING   => config('constant.toast.warn'),
            self::CANCELLED => config('constant.toast.error'),
            self::REJECTED  => config('constant.toast.error'),
        };
    }
}
