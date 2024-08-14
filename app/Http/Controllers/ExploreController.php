<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $tours = Tour::with('bookmarks', 'destinations', 'packages', 'reviews',)->get(); 

        return Inertia::render('Explore', [
            'tours' => $tours,
            'mode' => $request->query('mode', "0"),
            'flash' => session()->only(['status', 'message']),
        ]);
    }
    
    public function show($tours)
    {   
        $ids = explode(',', $tours);

        return Inertia::render('Compare/Tour', [
            'tours' => Tour::with('destinations')->whereIn('id', $ids)->get(),
        ]);
    }
}
