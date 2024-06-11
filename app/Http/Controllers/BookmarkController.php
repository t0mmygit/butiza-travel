<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class BookmarkController extends Controller
{
    public function store(Request $request, $tourId)
    {
        if (Auth::check()) {
            $userId = $request->user()->id;
        } else {
            if (!Cookie::has('guest_id')) {
                $guestId = Str::uuid()->toString();
                Cookie::queue('guest_id', $guestId, 60 * 24 * 7);
            } else {
                $guestId = Cookie::get('guest_id');
            }
        }

        $bookmark = Bookmark::make([
            'user_id' => Auth::check() ? $userId : $guestId, 
            'tour_id' => $tourId,
        ]);

        $exists = Bookmark::where('user_id', $bookmark['user_id'])
                            ->where('tour_id', $bookmark['tour_id'])
                            ->exists();

        if (!$exists) {
            if (Auth::check()) {
                $user = User::find($userId);
                $user->bookmarks()->save($bookmark);
            }
            $bookmark->save();
        }
    }

    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();
    }
}
