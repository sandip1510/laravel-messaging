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
});




// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');



// // Example API route
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/messages/{receiverId}', [MessageController::class, 'index']);
//     Route::post('/messages', [MessageController::class, 'store']);
// });

// Route::middleware('auth')->get('/messages', function () {
//     return view('messages');
// });
// // Apply Sanctum middleware to API routes
// Route::middleware([
//     EnsureFrontendRequestsAreStateful::class, // Allows SPA authentication
//     'auth:sanctum'
// ])->group(function () {
    
//     // User authentication routes
//     Route::get('/user', [AuthController::class, 'user']);
//     Route::post('/logout', [AuthController::class, 'logout']);

//     // Messaging routes
//     Route::get('/messages/{receiverId}', [MessageController::class, 'index']);
//     Route::post('/messages', [MessageController::class, 'store']);

//     // Fetch all users
//     Route::get('/users', [UserController::class, 'index']);
// });

