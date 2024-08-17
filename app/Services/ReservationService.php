<?php

namespace App\Services;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use App\Notifications\UserReservationNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;

class ReservationService
{
    public function storeReservation(ReservationRequest $request): Reservation
    {
        $reservation = $this->createReservation($request);

        $user = $this->attachUserToReservation($reservation);

        $this->handleNotification($user, $reservation);

        return $reservation;
    }

    private function createReservation(ReservationRequest $request): Reservation
    {
        $data = $request->only([
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
            'amount',
            'note',
        ]);
        
        return Reservation::create($data);
    }

    private function attachUserToReservation(Reservation $reservation): User | null
    {
        if (! auth()->check()) {
            return null;
        }

        $user = User::findOrFail(auth()->id());
        $user->reservations()->attach($reservation->id);

        return $user;
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