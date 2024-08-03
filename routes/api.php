<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\ContactSubmissionController;
use App\Http\Controllers\Api\HeaderController;
use App\Http\Controllers\Api\FooterController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ServiceController;

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

// Public routes
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LogoutController::class, 'logout']);


// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Blog routes
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::post('/create', [BlogController::class, 'store'])->name('create');
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/{id}', [BlogController::class, 'show'])->name('show');
        Route::put('/{id}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlogController::class, 'destroy'])->name('destroy');
        Route::get('/search', [BlogController::class, 'search'])->name('search');
        Route::get('/paginate', [BlogController::class, 'paginate'])->name('paginate');
    });

    // User routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Gallery routes
    Route::prefix('galleries')->name('galleries.')->group(function () {
        Route::post('/create', [GalleryController::class, 'store'])->name('store');
        Route::get('/', [GalleryController::class, 'index'])->name('index');
        Route::get('/{id}', [GalleryController::class, 'show'])->name('show');
        Route::put('/{id}', [GalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('destroy');
        Route::post('/search', [GalleryController::class, 'search'])->name('search');
    });

    // Contact Submission routes
    Route::prefix('contact')->name('contact')->group(function () {
        Route::post('/', [ContactSubmissionController::class, 'store'])->name('store');
        Route::get('/', [ContactSubmissionController::class, 'index'])->name('index');
        Route::get('/{id}', [ContactSubmissionController::class, 'show'])->name('show');
        Route::put('/{id}', [ContactSubmissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [ContactSubmissionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('header')->name('header.')->group(function () {
        Route::get('/', [HeaderController::class, 'index'])->name('index');
        Route::put('/', [HeaderController::class, 'update'])->name('update');
    });

    Route::prefix('footer')->group(function () {
        Route::get('/', [FooterController::class, 'index']);
        Route::post('/', [FooterController::class, 'store']);
        Route::put('/{id}', [FooterController::class, 'update']);
        Route::delete('/{id}', [FooterController::class, 'destroy']);
    });

    Route::prefix('about-us')->group(function () {
        Route::get('/', [AboutUsController::class, 'index']);
        Route::post('/{id}', [AboutUsController::class, 'update']);
        Route::post('/', [AboutUsController::class, 'store']); // Add this line
    });


    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']); // List all projects
        Route::get('/{id}', [ProjectController::class, 'show']); // Get a single project
        Route::post('/', [ProjectController::class, 'store']); // Create a new project
        Route::put('/{id}', [ProjectController::class, 'update']); // Update a project
        Route::delete('/{id}', [ProjectController::class, 'destroy']); // Delete a project
        });

    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index']);      // Get all clients
        Route::post('/', [ClientController::class, 'store']);     // Create a new client
        Route::get('/{client}', [ClientController::class, 'show']); // Get a single client
        Route::post('/{client}', [ClientController::class, 'update']); // Update a client
        Route::delete('/{client}', [ClientController::class, 'destroy']); // Delete a client
    });

       // Service routes
       Route::prefix('services')->name('services.')->group(function () {
        Route::post('/create', [ServiceController::class, 'store'])->name('create');
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/{id}', [ServiceController::class, 'show'])->name('show');
        Route::post('/{id}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
    });
});
