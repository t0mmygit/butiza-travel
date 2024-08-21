<?php

namespace App\Http\Controllers;

use App\Models\Tour;
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

    public function show(Tour $tour): Response
    {

        $tour->load([
            'availabilities' => function ($query) {
                $query->orderBy('departure_date', 'asc');
            },
            'bookmarks:id,user_id',
            'destinations:id,name',
            'highlights',
            'itinerary.days:id,day_number,day_title,description', 
            'note.subjects.bulletPoints',
            'reviews' => [
                'user:id,first_name,last_name',
                'tour',
            ],
            'discount:id,percentage',
            'packages.activities:name',
            'pickupLocation',
        ])->get();

        return Inertia::render('Tour/Index', [
            'tour' => $tour,
        ]);
    }
}
