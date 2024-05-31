<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\GroupTour;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Inertia\Inertia;

class GroupTourController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('id');

        return Inertia::render('HostGroupTour', [
            'tour' => Tour::where('id', $query)->get()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $oneMonth = Carbon::now()->addMonth()->toDateString();

        $validated = $request->validate([
            'tour_id'    => 'required|numeric',
            'date'        => 'required|date|after:' . $oneMonth,
            'max_passenger'   => 'required|numeric',
            'description' => 'required|string',
            'is_private' => 'required|boolean',
            'is_locked' => 'required|boolean'
        ]);

        $groupTour = GroupTour::create([
            'tour_id' => $validated['tour_id'],
            'user_id' => $request->user()->id,
            'date' => date('Y-m-d', strtotime($validated['date'])),
            'max_passenger' => $validated['max_passenger'],
            'description' => $validated['description'],
            'is_private' => $validated['is_private'],
            'is_locked' => $validated['is_locked']
        ]);

        $groupTour->tour()->associate($groupTour['tour_id']);
        $groupTour->user()->associate($groupTour['user_id']);
        $groupTour->save();

        return redirect(route('community'));
    }
}
