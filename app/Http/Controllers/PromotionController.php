<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PromotionController extends Controller
{
    public function index(): Response
    {
        $tour = \App\Models\Tour::has('discount')->get();

        return Inertia::render('Promotion', [
            'tours' => $tour->load([
                'discount:id,percentage',
                'packages',
            ]),
        ]);
    }
}