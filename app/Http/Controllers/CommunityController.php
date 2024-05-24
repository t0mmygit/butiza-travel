<?php

namespace App\Http\Controllers;

use App\Models\GroupTour;
use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class CommunityController extends Controller
{
    public function index()
    {
        $groupTours = GroupTour::with('tour')->get();

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

    public function createGroupTour(Request $request)
    {
        $oneMonth = Carbon::now()->addMonth()->toDateString();

        $validated = $request->validate([
            'tours_id'    => 'required|numeric',
            'date'        => 'required|date|after:' . $oneMonth,
            'passenger'   => 'required|numeric',
            'description' => 'required|string'
        ]);

        dd($request->user()->id);

        $groupTour = GroupTour::create([
            'tours_id' => $validated['tours_id'],
            'users_id' => $request->user()->id,
            'date' => date('Y-m-d', strtotime($validated['date'])),
            'passenger' => $validated['passenger'],
            'description' => $validated['description']
        ]);

        $groupTour->tour()->associate($validated['tours_id']);
        $groupTour->user()->associate($validated['users_id']);
        $groupTour->save();

        return redirect(route('community.index'));
    }
}
