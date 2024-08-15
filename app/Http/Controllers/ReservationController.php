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

    /**
     * Show the form for creating the resource.
     */
    public function create(Tour $tour): Response
    {
        $tour->load([
            'packages.activities',
            'pickupLocation'
        ]);

        return Inertia::render('Tour/Reserve', [
            'tour'            => $tour,
            'contact_methods' => ContactMethod::all(),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(ReservationRequest $request): RedirectResponse
    {
        $reservation = $this->reservationService->storeReservation($request);

        session()->flash('status', config('constant.toast.success'));
        session()->flash('message', 'Reservation '. $reservation->id .' created successfully.');

        return auth()->check()
            ? redirect(route('profile.history', ['model' => 'reservation'], absolute: false))
            : redirect(route('explore.index', absolute: false));
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}