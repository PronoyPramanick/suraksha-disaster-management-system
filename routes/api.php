<?php

use App\Http\Controllers\Api\V1\DisasterAlertController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/alerts', [DisasterAlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/{alert}', [DisasterAlertController::class, 'show'])->whereNumber('alert')->name('alerts.show');
});
