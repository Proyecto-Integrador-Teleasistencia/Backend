<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Backend routes
    Route::prefix('backend')->name('backend.')->group(function () {
        // Zones routes
        Route::resource('zonas', \App\Http\Controllers\Backend\ZonasController::class);
        
        // Operators routes
        Route::resource('operators', \App\Http\Controllers\Backend\OperatorsController::class);
    });
});

require __DIR__.'/auth.php';
