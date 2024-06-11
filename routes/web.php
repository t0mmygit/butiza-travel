<?php

use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\GroupTourController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CommunityAuthenticate;

Route::resource('/', MainController::class)
->only(['index']);

Route::get('/', function () {
   return Inertia::render('Main'); 
})->name('main');

Route::get('community', [CommunityController::class, 'index'])->name('community');

Route::get('/customize', function () {
    return Inertia::render('Customize');
})->name('customize');

Route::post('/send-email', [AuthController::class, 'sendEmail'])->name('auth.email');
Route::post('/send-password', [AuthController::class, 'sendPassword'])->name('auth.password');
Route::post('/send-detail', [AuthController::class, 'sendUserDetail'])->name('signup.detail');
Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.destroy');

Route::get('/reserve', [TourController::class, 'showReserveForm'])->name('tour.reserve');
Route::get('/book/{availabilityId}', [TourController::class, 'showBookingForm'])->name('tour.book');
Route::post('/submit-reservation', [TourController::class, 'submitReservation'])->name('tour.submit-reservation');
Route::post('/validate-booking', [TourController::class, 'validateBooking'])->name('tour.validate-booking');
Route::post('/submit-booking/{id}', [TourController::class, 'storeBooking'])->name('tour.store-booking');

Route::resource('tour', TourController::class)->only(['index', 'show']);
Route::resource('explore', ExploreController::class)->only(['index', 'show']);

Route::post('bookmark/{tour}', [BookmarkController::class, 'store'])->name('bookmark.store');
Route::delete('bookmark{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmark.destroy');
Route::post('validate-payment', [PaymentController::class, 'validation'])->name('validate-payment');

Route::middleware('auth')->group(function () {
    Route::get('group-tour', [GroupTourController::class, 'index'])->name('group-tour.index');
    Route::get('group-tour/lobby', [GroupTourController::class, 'lobby'])->name('group-tour.lobby');
    Route::post('group-tour/create', [GroupTourController::class, 'store'])->name('group-tour.store');

    Route::post('post/create', [PostController::class, 'store'])->name('post.store');
    Route::delete('post/delete/{post}', [PostController::class, 'destroy'])->name('post.destroy');
}); 

Route::get('profile/bookmark', [ProfileController::class, 'bookmark'])->name('profile.bookmark');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.account');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('profile/history', [ProfileController::class, 'history'])->name('profile.history');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    
require __DIR__.'/auth.php';
