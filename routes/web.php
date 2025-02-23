<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome'); // Ensure 'resources/views/welcome.blade.php' exists
});

// ✅ Ensure Dashboard uses Blade, not Inertia
Route::get('/dashboard', function () {
    return view('dashboard'); // Ensure 'resources/views/dashboard.blade.php' exists
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Group authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Ensure authentication routes are loaded
require __DIR__.'/auth.php';

