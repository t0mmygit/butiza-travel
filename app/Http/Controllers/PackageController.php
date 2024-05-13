<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tour;

class PackageController extends Controller
{
    public function index() 
    {
        return Inertia::render('Package', [
            'tours' => Tour::all(),
        ]);
    }

    public function show($tour) 
    {
        return Inertia::render('Tour', [
            'tour' => Tour::where('id', $tour)->get(),
        ]);
    }
}
