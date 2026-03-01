<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\Admin\VerificationFileController;
use App\Http\Livewire\Pages\Profile\UpdateProfilePage;
use App\Http\Livewire\Pages\Profile\ViewProfilePage;
use App\Http\Livewire\Pages\Profile\PendingVerificationPage;
use App\Http\Livewire\Pages\BlocklistPage;
use App\Http\Livewire\Pages\DashboardPage;
use App\Http\Livewire\Pages\FavoritesPage;
use App\Http\Livewire\Pages\RoommateRequestsPage;
use App\Http\Livewire\Pages\Settings\AccountSettingsPage;
use App\Http\Livewire\Pages\Settings\ContactChannelsSettingsPage;
use App\Http\Livewire\Pages\Settings\NotificationsSettingsPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/select2', 'pages.select2')->name('select2');

Route::view('/', 'pages.welcome')->name('home');
Route::view('/about', 'pages.about')->name('about');
Route::view('/features', 'pages.features')->name('features');
Route::view('/privacy', 'pages.privacy-policy')->name('privacy');
Route::view('/rules-and-guidelines', 'pages.rules-and-guidelines')->name('rules');
Route::view('/terms-of-use', 'pages.terms')->name('terms');

Route::get('/contact-us', [ContactsController::class, 'index'])->name('contact');
Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs');
Route::middleware(['throttle:xhrFormRequest'])->group(function () {
    Route::post('/contact-us', [ContactsController::class, 'store']);
    Route::post('/faqs', [FaqsController::class, 'store']);
});


Route::middleware(['auth:sanctum', 'verified', 'account.not_suspended'])
    ->group(function () {
        Route::get('/profile/update', UpdateProfilePage::class)->name('profile.update');
        Route::get('/verification/pending', PendingVerificationPage::class)->name('verification.pending');

        Route::as('settings.')->prefix('settings')->group(function () {
            Route::get('/account', AccountSettingsPage::class)->name('account');
            Route::get('/contact-channels', ContactChannelsSettingsPage::class)->name('contact-channels');
            Route::get('/notifications', NotificationsSettingsPage::class)->name('notifications');
        });

        Route::middleware(['profile.updated', 'identity.verified'])->group(function () {
            Route::get('/profile/view/{user}', ViewProfilePage::class)->name('profile.view');

            Route::get('/dashboard', DashboardPage::class)->name('dashboard');
            Route::get('/favorites', FavoritesPage::class)->name('favorites');
            Route::get('/roommate-requests', RoommateRequestsPage::class)->name('roommate-requests');
            Route::get('/blocklist', BlocklistPage::class)->name('blocklist');
        });
    });

Route::middleware(['auth:sanctum', 'signed'])->group(function () {
    Route::get('/admin/verification-files/{user}/{type}', [VerificationFileController::class, 'show'])
        ->whereIn('type', ['identity', 'selfie'])
        ->name('admin.verification-file.show');
});

require __DIR__ . '/auth.php';
