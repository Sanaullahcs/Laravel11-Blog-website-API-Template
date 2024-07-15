<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SomeProtectedController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;


/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 */

 Route::middleware('api.auth')->group(function () {
    Route::prefix('createBlog')->group(function () {
        Route::post('/', [BlogController::class, 'store'])->name('createBlog');
    });

    Route::prefix('updateBlog')->group(function () {
        Route::put('/{id}', [BlogController::class, 'update'])->name('updateBlog');
    });

    Route::prefix('blogLists')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('blogLists');
        Route::get('/{id}', [BlogController::class, 'show'])->name('blogLists.show');
    });

    Route::prefix('deleteBlog')->group(function () {
        Route::delete('/{id}', [BlogController::class, 'destroy'])->name('deleteBlog');
    });
});

// Public route for registration
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});


// Secured routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('protected-route', [SomeProtectedController::class, 'index']);
    // Add more protected routes here
});




