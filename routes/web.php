<?php

use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MainController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::resource('/', MainController::class)
    ->only(['index']);

Route::get('/', function () {
   return Inertia::render('Main'); 
})->name('main');

Route::get('/customize', function () {
    return Inertia::render('Customize');
})->name('customize');

Route::resource('/package', PackageController::class)
    ->only(['index', 'show']);

Route::resource('/explore', ExploreController::class)
    ->only(['index', 'show']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
