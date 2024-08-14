<?php

namespace App\Http\Controllers;

use App\Models\ContactMethod;
use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
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
            'tour' => $tour,
            'contact_methods' => ContactMethod::all(),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
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