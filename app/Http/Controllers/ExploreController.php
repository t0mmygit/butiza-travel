<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    public function index()
    {
        $tours = Tour::with('destinations')->get();

        return Inertia::render('Explore', [
            'tours' => $tours
        ]);
    }
    
    public function show($tours)
    {   
        $ids = explode(',', $tours);

        return Inertia::render('CompareTour', [
            'tours' => Tour::whereIn('id', $ids)->get()
        ]);
    }
}
