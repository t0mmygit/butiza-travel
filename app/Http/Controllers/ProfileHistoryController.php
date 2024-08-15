<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileHistoryController extends Controller
{
    protected User $user;

    public function index(?string $model = 'booking'): Response
    {
        $this->user = User::find(Auth::id());

        return Inertia::render('Profile/History', [
            'model' => $model,
            'history' => $this->getHistory($model),
            'flash' => session()->only(['status', 'message']),
        ]);
    }

    protected function getHistory(string $model): Collection
    {
        return match ($model) {
            'reservation' => $this->getReservationHistory(),
            'payment' => $this->getPaymentHistory(),
            default => $this->getBookingHistory(),
        };
    }

    protected function getBookingHistory(): Collection
    {
        $bookings = $this->user->bookings()->get();

        return $bookings->load(['package.tour']);
    }

    protected function getReservationHistory(): Collection
    {
        $reservations = $this->user->reservations()->get();

        return $reservations->load(['package.tour']);
    }

    protected function getPaymentHistory(): Collection
    {
        $payment = $this->user->payments()->get();

        return $payment;
    }
}