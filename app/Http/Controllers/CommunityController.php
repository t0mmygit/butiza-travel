<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index()
    {
        return Inertia::render('Community');
    }

    public function host()
    {
        return Inertia::render('HostGroupTour');
    }
}
