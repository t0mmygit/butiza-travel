<?php

namespace App\Http\Controllers;

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

Route::get('/community', [CommunityController::class, 'index'])->name('community');

Route::controller(UploadController::class)->group(function () {
    Route::post('/upload', 'store')->name('upload.store');
    Route::delete('/upload/{path}', 'destroy')->name('upload.destroy');
});

Route::resource('/explore', ExploreController::class)->only(['index', 'show']);
Route::resource('tour', TourController::class)->only(['index', 'show']);

Route::controller(CustomizeController::class)->group(function () {
    Route::get('/customize', 'index')->name('customize');
    Route::post('/customize/store', 'store')->name('customize.store');
});

Route::controller(TourController::class)->group(function () {
    Route::get('/reserve', 'showReserveForm')->name('tour.reserve');
    Route::post('/submit-reservation', 'submitReservation')->name('tour.submit-reservation');
});

Route::controller(ReservationController::class)->group(function () {
    Route::get('/reservation/{tour:slug}', 'create')->name('reservation.create');
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
    Route::get('/payment/{id}', 'create')->name('payment.create');
    Route::patch('/payment/{payment}', 'update')->name('payment.update'); 
});

Route::controller(ReviewController::class)->group(function () {
    Route::post('/profile/review/{tour}', 'store')->name('review.store');
});

Route::controller(PartnerRequestController::class)->group(function () {
    Route::get('/partner', 'create')->name('partner.create');
    Route::post('/partner', 'store')->name('partner.store');
});

Route::post('/partner/validate', PartnerRequestValidationController::class)
    ->name('partner.validate');

Route::controller(PartnerController::class)->group(function () {
    Route::get('/partner/account/create', 'create')->name('partner-account.create');
    Route::post('/partner/account/create', 'store')->name('partner-account.store');
});

Route::middleware('partner')->group(function () {
    Route::controller(PartnerController::class)->group(function () {
        Route::get('/partner/account', 'index')->name('partner-account');
        Route::patch('partner/account/{partner}', 'update')->name('partner-account.update');
    });

    Route::post('partner/account/booking', [PartnerBookingController::class, 'store'])
            ->name('partner-account-booking.store');

    Route::patch('partner/preference/{partner}', [PartnerPreferenceController::class, 'update'])
            ->name('partner-preference.update');
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
        Route::get('profile', 'index')->name('profile.account');
        Route::get('profile/edit', 'edit')->name('profile.edit');

        Route::get('profile/review', 'review')->name('profile.review');
        Route::patch('profile', 'update')->name('profile.update');
        Route::delete('profile', 'destroy')->name('profile.destroy');
    });

    Route::get('profile/history/{model?}', [ProfileHistoryController::class, 'index'])
        ->name('profile.history')
        ->where('model', 'booking|reservation|payment');
});
    
require __DIR__.'/auth.php';