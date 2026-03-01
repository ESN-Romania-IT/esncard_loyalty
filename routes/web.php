<?php
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Business\BusinessDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ClientRedemptionController;
use App\Http\Controllers\Admin\OfferRedemptionController;
use App\Http\Controllers\Business\BusinessOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::view('/terms-and-conditions', 'terms-and-conditions')->name('terms-and-conditions');

Route::view('/about', 'about')->name('about');

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

            Route::get('/offers', [BusinessOfferController::class, 'index'])
                ->name('offers.index');

            Route::get('/offers/create', [BusinessOfferController::class, 'create'])
                ->name('offers.create');

            Route::post('/offers', [BusinessOfferController::class, 'store'])
                ->name('offers.store');

            Route::get('/offers/{offer}', [BusinessOfferController::class, 'show'])
                ->name('offers.show');

            Route::get('/offers/{offer}/edit', [BusinessOfferController::class, 'edit'])
                ->name('offers.edit');

            Route::put('/offers/{offer}', [BusinessOfferController::class, 'update'])
                ->name('offers.update');

            Route::post('/qr/verify', [BusinessDashboardController::class, 'verifyQr'])
                ->name('qr.verify');

            Route::get('/qr/open', [BusinessDashboardController::class, 'openQr'])
                ->name('qr.open');

            Route::get('/qr/offers', [BusinessDashboardController::class, 'offers'])
                ->name('qr.offers');

            Route::post('/qr/redeem', [BusinessDashboardController::class, 'redeem'])
                ->name('qr.redeem');
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
Route::get('/qr-reader', function () {
    return view('QrReader');
})->name('qr.reader');
