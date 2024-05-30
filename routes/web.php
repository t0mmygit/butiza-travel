<?php

use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
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

Route::post('/send-email', [AuthController::class, 'sendEmail'])->name('auth.email');
Route::post('/send-password', [AuthController::class, 'sendPassword'])->name('auth.password');
Route::post('/send-detail', [AuthController::class, 'sendUserDetail'])->name('signup.detail');
Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.destroy');


Route::get('/customize', function () {
    return Inertia::render('Customize');
})->name('customize');

Route::get('/reserve', [TourController::class, 'showReserveForm'])->name('tour.reserve');
Route::get('/book/{availabilityId}', [TourController::class, 'showBookingForm'])->name('tour.book');
Route::post('/submit-reservation', [TourController::class, 'submitReservation'])->name('tour.submit-reservation');
Route::post('/validate-booking', [TourController::class, 'validateBooking'])->name('tour.validate-booking');
Route::resource('/tour', TourController::class)
    ->only(['index', 'show']);

Route::resource('/explore', ExploreController::class)
    ->only(['index', 'show']);
    
Route::get('/community', [CommunityController::class, 'index'])->name('community');

Route::middleware('auth')->group(function () {
    Route::get('/host', [CommunityController::class, 'host'])->name('host.index');
    Route::post('/create-group-tour', [CommunityController::class, 'createGroupTour'])->name('host.create');
    Route::post('/create_post', [PostController::class, 'store'])->name('post.store');
});

Route::post('/validate-payment', [PaymentController::class, 'validation'])->name('validate-payment');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
