<?php

namespace App\Http\Controllers;

use App\Models\GroupTour;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', null);
        $cursor = $request->query('cursor', null);
        $perPage = 8;

        switch($type) 
        {
            case 'group_tour':
                $posts = GroupTour::with(['user', 'tour'])
                            ->select('*', DB::raw("'group_tour' as type"))
                            ->orderBy('created_at', 'desc')
                            ->cursorPaginate($perPage);
                            break;
            case 'normal_post':
                $posts = Post::with('user')
                            ->select('*', DB::raw("'normal_post' as type"))
                            ->orderBy('created_at', 'desc')
                            ->cursorPaginate($perPage);
                            break;
            default:
                $groupTourQuery = GroupTour::with(['user', 'tour'])
                                    ->select('*', DB::raw("'group_tour' as type"))->get();
                $normalPostQuery = Post::with('user')
                                    ->select('*', DB::raw("'normal_post' as type"))->get();
                
                $posts = $groupTourQuery->concat($normalPostQuery)
                            ->sortByDesc('created_at')
                            ->values();

                $posts = $this->cursorPaginateCollection($posts, $perPage, $cursor);
        }

        if ($request->expectsJson()) {
            return response()->json($posts);
        }
        return Inertia::render('Community', [
            'posts' => $posts
        ]);
    }

    protected function cursorPaginateCollection(Collection $collection, $perPage, $cursor)
    {
        $cursorIndex = $collection->search(function ($item) use ($cursor) {
            return $item['created_at']->toDateTimeString() == $cursor;
        });

        $sliceStart = $cursorIndex === false ? 0 : $cursorIndex + 1;

        // Addition of 1 $perPage to store the 1st index of next page
        $slicedCollection = $collection->slice($sliceStart, $perPage + 1);
        $nextCursor = $slicedCollection->count() > $perPage
                        ? $slicedCollection->last()['created_at']->toDateTimeString()
                        : null;

        return [
            'data' => $slicedCollection->take($perPage)->values(), 
            'next_cursor' => $nextCursor 
        ];
    }
}
