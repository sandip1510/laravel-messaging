<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;


use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return User::where('id', '!=', $request->user()->id)->get();
});

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Messaging Routes
    Route::get('/messages/{receiverId}', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);

    // Fetch Users
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/authUser', [UserController::class, 'authUser']);
});

