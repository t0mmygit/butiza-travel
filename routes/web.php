<?php

use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\GroupTourController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
   return Inertia::render('Home'); 
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/collaboration', function () {
    return Inertia::render('Collab');
})->name('collab');

Route::get('/question', function () {
   return Inertia::render('Question'); 
})->name('question');

Route::get('/customize', function () {
    return Inertia::render('Customize');
})->name('customize');

Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::resource('tour', TourController::class)->only(['index', 'show']);
Route::resource('explore', ExploreController::class)->only(['index', 'show']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/send-email', 'sendEmail')->name('auth.email');
    Route::post('/send-password', 'sendPassword')->name('auth.password');
    Route::post('/send-detail', 'sendUserDetail')->name('signup.detail');
    Route::delete('/logout', 'logout')->name('auth.destroy');
});

Route::controller(TourController::class)->group(function () {
    Route::get('/reserve', 'showReserveForm')->name('tour.reserve');
    Route::get('/book/{availabilityId}', 'showBookingForm')->name('tour.book');
    Route::post('/submit-reservation', 'submitReservation')->name('tour.submit-reservation');
    Route::post('/validate-booking', 'validateBooking')->name('tour.validate-booking');
    Route::post('/submit-booking/{id}', 'storeBooking')->name('tour.store-booking');
});

Route::get('/profile/bookmark', [ProfileController::class, 'bookmark'])->name('profile.bookmark');
Route::post('/bookmark/{tour}', [BookmarkController::class, 'store'])->name('bookmark.store');
Route::post('/validate-payment', [PaymentController::class, 'validation'])->name('validate-payment');
Route::post('/customer-query', [CustomerSupportController::class, 'store'])->name('support.store');
Route::delete('/bookmark/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');

Route::controller(BookingController::class)->group(function () {
    Route::patch('/profile/booking/{booking}', 'update')->name('booking.update');
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/profile/review/{tour}', 'store')->name('review.store');
});
// Route::patch('/profile/review/{review}')->name('review.update');

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
        // Route::get('/profile', 'index')->name('profile.account');
        Route::get('/profile', 'index')->name('profile.account');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::get('/profile/history', 'history')->name('profile.history');
        Route::get('/profile/review', 'review')->name('profile.review');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
}); 
    
require __DIR__.'/auth.php';