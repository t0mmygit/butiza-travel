<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\ContactMethod;
use App\Models\Reservation;
use App\Models\Tour;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function create(Tour $tour): Response
    {
        $tour->load([
            'packages.activities',
            'pickupLocation',
            'discount:id,type,percentage'
        ]);

        return Inertia::render('Tour/Reserve', [
            'tour'            => $tour,
            'contact_methods' => ContactMethod::all(),
        ]);
    }

    public function store(ReservationRequest $request): RedirectResponse
    {
        $reservation = $this->reservationService->storeReservation($request);

        session()->flash('status', config('constant.toast.success'));
        session()->flash('message', 'Reservation '. $reservation->id .' created successfully.');

        return auth()->check()
            ? redirect(route('profile.history', ['model' => 'reservation'], absolute: false))
            : redirect(route('explore.index', absolute: false));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        // TODO: Authorize authenticated user

        $reservation->update([
            'status' => $request->input('status'),
        ]);

        // TODO: Handle reservation cancellation

        return redirect(route('profile.history', ['model' => 'reservation'], absolute: false));
    }
}