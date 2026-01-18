<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\StudentAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest:student')->group(function () {

    Route::get('/register/{id}', [StudentAuthController::class, 'register'])->name('register');

    Route::post('/register/{id}', [StudentAuthController::class, 'register_submit'])->name('register.submit');


    Route::get('/login', [StudentAuthController::class, 'loginForm'])->name('login.form');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.submit');
});


Route::middleware('auth:student')->group(function () {

    Route::get('/me', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/me/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    Route::get('/me/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');

    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

});


Route::get('/info/terms', [InfoController::class, 'terms_and_conditions'])->name('terms-and-conditions');

Route::get('/about', [InfoController::class, 'about'])->name('about');

Route::middleware('guest:web')->group(function () {
    Route::get('admin/login', function () {
        return view('admin.login');
    });
});

Route::middleware('auth:web')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
