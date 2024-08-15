<?php

namespace App\Services;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use App\Notifications\UserReservationNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class ReservationService
{
    public function storeReservation($request): Reservation
    {
        $reservation = $this->createReservation($request);

        $user = $this->getUser();

        if ($user instanceof User) {
            $user->reservations()->attach($reservation->id);
        }

        $this->handleNotification($user, $reservation);

        return $reservation;
    }

    private function createReservation(ReservationRequest $request): Reservation
    {
        $reservation = Reservation::create(
            $request->only([
                'package_id',
                'contact_method_id',
                'discount_id',
                'pickup_location_id',
                'preferred_date',
                'adult',
                'child',
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'note',
                'amount',
            ])
        );

        return $reservation;
    }

    private function getUser(): User | null
    {
        return auth()->check() ? User::findOrFail(auth()->id()) : null;
    }

    private function handleNotification(?User $user, Reservation $reservation)
    {
        if ($user instanceof User) {
            $user->notify(new UserReservationNotification($reservation));
        } else {
            Notification::route('mail', $reservation->email)
                ->notify(new UserReservationNotification($reservation));
        }

        $this->notifyAdmin($reservation);
    }

    private function notifyAdmin(Reservation $reservation)
    {
        Notification::send($this->getAdmins(), new NewReservationNotification($reservation));
    }

    private function getAdmins(): Collection
    {
        return User::role(config('constant.role.admin'))->get();
    }
}