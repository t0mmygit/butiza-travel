<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomizeRequest;
use App\Models\Activity;
use App\Models\AgeRange;
use App\Models\Destination;
use App\Models\GuideType;
use App\Models\LodgeType;
use App\Models\TravelIntensity;
use App\Services\CustomizeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomizeController extends Controller
{
    protected $customizeService;

    public function __construct(CustomizeService $customizeService)
    {
        $this->customizeService = $customizeService;
    }

    public function index(): Response
    {
        return Inertia::render('Customize', [
            'destinations' => Destination::all(),
            'activities' => Activity::all(),
            'guide_types' => GuideType::all(),
            'lodge_types' => LodgeType::all(),
            'travel_intensities' => TravelIntensity::all(),
            'age_range' => AgeRange::all(),
        ]);
    }

    public function store(CustomizeRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $this->customizeService->create($validated);

        return redirect()->route('home');
    }
}
