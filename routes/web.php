<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('public/Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('admin/station', [StationController::class, 'index'])->name('admin.station');
    Route::post('admin/station', [StationController::class, 'store']);
    Route::put('admin/station/{id}', [StationController::class, 'update']);
    Route::delete('admin/station/{id}', [StationController::class, 'destroy']);
});

Route::get('api/stations/search', [StationController::class, 'search'])->name('api.stations.search');

require __DIR__.'/settings.php';

Route::fallback(function () {
    return Inertia::render('errors/Error404');
});
