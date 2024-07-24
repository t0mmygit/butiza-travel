<?php

use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\CustomizeController;
use App\Http\Controllers\GroupTourController;
use App\Http\Controllers\PartnerAuthenticationController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ValidateBookingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    return Inertia::render('Home'); 
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/question', function () {
   return Inertia::render('Question'); 
})->name('question');

Route::resource('/explore', ExploreController::class)->only(['index', 'show']);
Route::get('/community', [CommunityController::class, 'index'])->name('community');

Route::resource('tour', TourController::class)->only(['index', 'show']);

Route::controller(CustomizeController::class)->group(function () {
    Route::get('/customize', 'index')->name('customize');
    Route::post('/customize/store', 'store')->name('customize.store');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'store')->name('signup.detail');
    Route::delete('/logout', 'destroy')->name('auth.destroy');
});

Route::controller(TourController::class)->group(function () {
    Route::get('/reserve', 'showReserveForm')->name('tour.reserve');
    Route::post('/submit-reservation', 'submitReservation')->name('tour.submit-reservation');
});

Route::get('/profile/bookmark', [ProfileController::class, 'bookmark'])->name('profile.bookmark');
Route::post('/bookmark/{tour}', [BookmarkController::class, 'store'])->name('bookmark.store');
Route::post('/customer-query', [CustomerSupportController::class, 'store'])->name('support.store');
Route::delete('/bookmark/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');

Route::controller(BookingController::class)->group(function () {
    Route::get('/book/{availabilityId}', 'show')->name('booking.show');
    Route::post('/book/{availability}', 'store')->name('booking.store');
    Route::patch('/profile/booking/{booking}', 'update')->name('booking.update');
});

Route::controller(PaymentController::class)->group(function () {
    Route::get('/payment/{id}', 'show')->name('payment.show');
    Route::patch('/payment/{id}', 'update')->name('payment.update'); 
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/profile/review/{tour}', 'store')->name('review.store');
});

Route::controller(PartnerRequestController::class)->group(function () {
    Route::get('/partner', 'create')->name('partner.create');
    Route::post('/partner', 'store')->name('partner.store');
});

Route::controller(PartnerController::class)->group(function () {
    Route::get('/partner/account/create', 'create')->name('partner-account.create');
    Route::post('/partner/account/create', 'store')->name('partner-account.store');

    Route::middleware('partner')->group(function () {
        Route::get('/partner/account', 'index')->name('partner-account');
    });
});

Route::controller(PartnerAuthenticationController::class)->group(function () {
    Route::get('/partner/login', 'create')->name('partner-login.create');
    Route::post('/partner/login', 'store')->name('partner-login.store');
});

Route::middleware('auth')->group(function () {
    Route::controller(GroupTourController::class)->group(function () {
        Route::get('/group-tour', 'index')->name('group-tour.index');
        Route::get('/group-tour/lobby', 'lobby')->name('group-tour.lobby');
        Route::post('/group-tour/create', 'store')->name('group-tour.store');
        Route::delete('/group-tour/delete/{groupTour}', 'destroy')->name('group-tour.destroy');
    });
    
    Route::controller(PostController::class)->group(function () {
        Route::post('/post/create', 'store')->name('post.store');
        Route::delete('/post/delete/{post}', 'destroy')->name('post.destroy');
    });
    
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.account');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::get('/profile/history', 'history')->name('profile.history');
        Route::get('/profile/review', 'review')->name('profile.review');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});
    
require __DIR__.'/auth.php';