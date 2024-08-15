<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Tour;
use App\Models\Reservation;
use App\Models\ContactMethod;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Package', [
            'tours' => Tour::all(),
        ]);
    }

    public function show($tourId): Response
    {
        $tour = Tour::with([
            'availabilities' => function ($query) {
                $query->orderBy('departure_date', 'asc');
            },
            'bookmarks:id,user_id',
            'destinations:id,name',
            'highlights:id,description',
            'itinerary.days:id,day_number,day_title,description', 
            'note.subjects.bulletPoints',
            'reviews' => [
                'user:id,first_name,last_name',
                'tour',
            ],
            'packages.activities:name',
            'pickupLocation',
        ])->findOrFail($tourId);

        return Inertia::render('Tour/Index', [
            'tour' => $tour,
        ]);
    }
}
