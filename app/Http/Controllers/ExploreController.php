<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExploreController extends Controller
{
    public function index()
    {
        return Inertia::render('Explore', [
            'tours' => Tour::all()
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
