<?php

namespace App\Http\Controllers;

use App\Models\GroupTour;
use App\Models\Post;
use App\Models\Tour;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        // $type = $request->query('type', null);
        $type = 'group_tour';
        $perPage = 10;

        switch($type) 
        {
            case 'group_tour':
                $posts = GroupTour::with(['user', 'tour'])
                            ->select('*', DB::raw("'group_tour' as type"))
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
                            break;
            case 'normal_post':
                $posts = Post::with('user')
                            ->select('*', DB::raw("'normal_post' as type"))
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
                            break;
            default:
                $groupTourQuery = GroupTour::with(['user', 'tour'])
                                    ->select('*', DB::raw("'group_tour' as type"));
                $normalPostQuery = Post::with('user')
                                    ->select('*', DB::raw("'normal_post' as type"));
                
                $posts = $groupTourQuery->union($normalPostQuery)
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);;
        }
        // dd($posts);

        return Inertia::render('Community', [
            'posts' => $posts
        ]);
    }

    public function host(Request $request)
    {
        $query = $request->query('id');

        return Inertia::render('HostGroupTour', [
            'tour' => Tour::where('id', $query)->get()
        ]);
    }

    public function createGroupTour(Request $request): RedirectResponse
    {
        $oneMonth = Carbon::now()->addMonth()->toDateString();

        $validated = $request->validate([
            'tour_id'    => 'required|numeric',
            'date'        => 'required|date|after:' . $oneMonth,
            'max_passenger'   => 'required|numeric',
            'description' => 'required|string'
        ]);

        $groupTour = GroupTour::create([
            'tour_id' => $validated['tour_id'],
            'user_id' => $request->user()->id,
            'date' => date('Y-m-d', strtotime($validated['date'])),
            'max_passenger' => $validated['max_passenger'],
            'description' => $validated['description']
        ]);

        $groupTour->tour()->associate($groupTour['tour_id']);
        $groupTour->user()->associate($groupTour['user_id']);
        $groupTour->save();

        return redirect(route('community'));
    }

    public function login()
    {
        return Inertia::render('CommunityLogin');
    }
}
