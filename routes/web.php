<?php
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Business\BusinessDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientRedemptionController;
use App\Http\Controllers\Admin\OfferRedemptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::get('/info/terms', [InfoController::class, 'terms_and_conditions'])->name('terms-and-conditions');
Route::get('/about', [InfoController::class, 'about'])->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/me', function () {
        $user = request()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'business_user' => redirect()->route('business.dashboard'),
            default => redirect()->route('client.dashboard'),
        };
    })->name('me');

    // CLIENT (standard_user)
    Route::middleware('role:standard_user')
        ->prefix('me')
        ->name('client.')
        ->group(function () {
            Route::get('/dashboard', [ClientDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // BUSINESS (business_user)
    Route::middleware('role:business_user')
        ->prefix('business')
        ->name('business.')
        ->group(function () {
            Route::get('/dashboard', [BusinessDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // ADMIN (admin)
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('users', UserController::class);

            Route::resource('clients', ClientController::class)->only(['index','show']);
            Route::resource('clients.redemptions', ClientRedemptionController::class)->only(['store', 'destroy']);
            Route::delete('clients/{client}/redemptions', [ClientRedemptionController::class, 'destroyForOffer'])->name('clients.redemptions.destroyForOffer');

            Route::resource('businesses', BusinessController::class)->only(['index','show']);
            Route::resource('businesses.offers', OfferController::class);
            Route::resource('businesses.offers.redemptions', OfferRedemptionController::class)->only(['store', 'destroy']);
            Route::delete('businesses/{business}/offers/{offer}/redemptions', [OfferRedemptionController::class, 'destroyForClient'])->name('businesses.offers.redemptions.destroyForClient');
        });
});
