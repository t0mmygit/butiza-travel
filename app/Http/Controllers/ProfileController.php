<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use App\Models\Bookmark;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(): Response
    {
        $userId = Auth::user()->id;
        $user = User::with('posts', 'reviews', 'bookings')->findOrFail($userId);

        return Inertia::render('Profile/Account', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // NOTE: NEED MODIFICATION
    public function history(Request $request): Response
    {
        $user = $request->user();

        $bookings = Booking::where('email', $user->email)->with([
            'tour.destinations',
            'tour.itineraries.days'
        ])->get();

        $statuses = array_map(function($status) {
            return [
                'name' => $status,
                'value' => $status->getValue(),
                'severity' => $status->getSeverity(),
            ];
        }, BookingStatus::cases());

        return Inertia::render('Profile/History', [
            'bookings' => $bookings,
            'reviews' => Review::all(), // THIS IS TERRIBLE FOR SCALING (will cause lag)
            'bookingStatuses' => $statuses,
            'reservations' => Reservation::where('email', $user->email)->with('tour')->get(),
            'payments' => null,
        ]);
    } 

    public function bookmark(Request $request): Response
    {
        if (Auth::check()) {
            $user = $request->user()->id;
        } else {
            if (Cookie::has('guest_id')) {
                $user = Cookie::get('guest_id');
            }
        }

        $bookmarks = Bookmark::where('user_id', $user)
                    ->with('tour')->get();
        
        return Inertia::render('Profile/Bookmark', [
            'bookmarks' => $bookmarks,
        ]);
    }

    public function review(Request $request): Response
    {
        $user = $request->user();

        $reviews = Review::with('tour', 'booking')->where('user_id', $user->id)->get();

        return Inertia::render('Profile/Review', [
            'reviews' => $reviews,
        ]);
    }
}
