<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\GroupTour;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class GroupTourController extends Controller
{
    public function index(Request $request): Response
    {
        $query = $request->query('id');

        return Inertia::render('GroupTour/Host', [
            'tour' => Tour::where('id', $query)->get()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $oneMonth = Carbon::now()->addMonth()->toDateString();
    
            $validated = $request->validate([
                'tour_id'       => 'required|numeric',
                'published_at'  => 'required|date|after:' . $oneMonth,
                'max_passenger' => 'required|numeric',
                'description'   => 'required|string',
                'is_private'    => 'required|boolean',
                'is_locked'     => 'required|boolean'
            ]);
    
            $groupTour = GroupTour::create([
                'tour_id'       => $validated['tour_id'],
                'user_id'       => $request->user()->id,
                'published_at'  => date('Y-m-d', strtotime($validated['published_at'])),
                'max_passenger' => $validated['max_passenger'],
                'description'   => $validated['description'],
                'is_private'    => $validated['is_private'],
                'is_locked'     => $validated['is_locked']
            ]);
    
            $groupTour->tour()->associate($groupTour['tour_id']);
            $groupTour->user()->associate($groupTour['user_id']);
            $groupTour->save();

            return redirect(route('community'));

        } catch (ValidationException $e) {
            Log::error('Validation error occurred in GroupTour store method: '.$e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error occurred in GroupTour store method: '.$e->getMessage());
        }
    }

    public function destroy(GroupTour $groupTour): RedirectResponse
    {
        $this->authorize('delete', $groupTour);

        $groupTour->delete();

        return redirect(route('community'));
    }

    public function lobby(): Response
    {
        return Inertia::render('GroupTour/Lobby', [
            //
        ]);
    }
}
