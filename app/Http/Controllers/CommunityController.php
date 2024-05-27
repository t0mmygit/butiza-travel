<?php

namespace App\Http\Controllers;

use App\Models\GroupTour;
use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class CommunityController extends Controller
{
    public function index()
    {
        $groupTours = GroupTour::with('tour', 'user')->get();

        return Inertia::render('Community', [
            'group_tours' => $groupTours
        ]);
    }

    public function host(Request $request)
    {
        $query = $request->query('id');

        return Inertia::render('HostGroupTour', [
            'tour' => Tour::where('id', $query)->get()
        ]);
    }

    public function createGroupTour(Request $request): RedirectResponse
    {
        $oneMonth = Carbon::now()->addMonth()->toDateString();

        $validated = $request->validate([
            'tour_id'    => 'required|numeric',
            'date'        => 'required|date|after:' . $oneMonth,
            'passenger'   => 'required|numeric',
            'description' => 'required|string'
        ]);

        $groupTour = GroupTour::create([
            'tour_id' => $validated['tour_id'],
            'user_id' => $request->user()->id,
            'date' => date('Y-m-d', strtotime($validated['date'])),
            'passenger' => $validated['passenger'],
            'description' => $validated['description']
        ]);

        $groupTour->tour()->associate($groupTour['tour_id']);
        $groupTour->user()->associate($groupTour['user_id']);
        $groupTour->save();

        return redirect(route('community.index'));
    }

    public function login()
    {
        return Inertia::render('CommunityLogin');
    }
}
