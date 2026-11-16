<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisasterAlertController;
use App\Http\Controllers\RescueRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/rescue-requests', [RescueRequestController::class, 'index'])->name('rescue-requests.index');
    Route::get('/rescue-requests/create', [RescueRequestController::class, 'create'])->name('rescue-requests.create');
    Route::post('/rescue-requests', [RescueRequestController::class, 'store'])->name('rescue-requests.store');
    Route::patch('/rescue-requests/{rescueRequest}/status', [RescueRequestController::class, 'updateStatus'])->middleware('admin')->name('rescue-requests.status');
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
