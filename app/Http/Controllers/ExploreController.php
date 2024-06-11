<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $tours = Tour::with('destinations', 'bookmarks')->get();

        return Inertia::render('Explore', [
            'tours' => $tours,
            'mode' => $request->query('mode', "0")
        ]);
    }
    
    public function show($tours)
    {   
        $ids = explode(',', $tours);

        return Inertia::render('Compare/Tour', [
            // 'tours' => Tour::whereIn('id', $ids)->get(),
            'tours' => Tour::with('destinations')->whereIn('id', $ids)->get(),
        ]);
    }
}
