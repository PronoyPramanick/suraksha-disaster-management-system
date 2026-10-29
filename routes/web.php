<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisasterAlertController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/alerts', [DisasterAlertController::class, 'index'])->name('alerts.index');

    Route::middleware('admin')->group(function () {
        Route::resource('alerts', DisasterAlertController::class)->except(['index', 'show']);
    });

    Route::get('/alerts/{alert}', [DisasterAlertController::class, 'show'])->name('alerts.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
