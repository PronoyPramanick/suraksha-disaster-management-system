<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisasterAlertController;
use App\Http\Controllers\RescueRequestController;
use App\Models\DisasterAlert;
use App\Models\RescueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/map', 'map.index')->name('map.index');
Route::get('/affected-areas', [DisasterAlertController::class, 'affectedAreas'])->name('affected-areas.index');

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    $requestQuery = RescueRequest::query()
        ->when($user->role !== 'admin', fn ($query) => $query->where('user_id', $user->id));

    $activeAlerts = DisasterAlert::query()->where('kind', 'alert')->where('status', 'active')->count();
    $rescueRequestCount = (clone $requestQuery)->count();
    $pendingRequestCount = (clone $requestQuery)->where('status', 'Pending')->count();
    $recentRequests = (clone $requestQuery)->latest('updated_at')->limit(4)->get();
    $recentAlerts = DisasterAlert::query()->where('kind', 'alert')->latest('published_at')->limit(3)->get();

    return view('dashboard', compact('user', 'activeAlerts', 'rescueRequestCount', 'pendingRequestCount', 'recentRequests', 'recentAlerts'));
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
