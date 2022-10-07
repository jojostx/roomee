<?php

use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ContactsController;
use App\Http\Livewire\Pages\Blocklist;
use App\Http\Livewire\Pages\Dashboard;
use App\Http\Livewire\Pages\DashboardFilament;
use App\Http\Livewire\Pages\Favorite;
use App\Http\Livewire\Pages\Profile\UpdatePage;
use App\Http\Livewire\Pages\Profile\ViewProfile;
use App\Http\Livewire\Pages\Request;
use Illuminate\Support\Facades\Route;


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


Route::middleware(['auth:sanctum', 'verified', 'profile.updated'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/dashboard-filament', DashboardFilament::class)->name('dashboard.filament');
    Route::get('/profile/update', UpdatePage::class)->withoutMiddleware(['profile.updated'])->name('profile.update');
    Route::get('/profile/view/{user}', ViewProfile::class)->name('profile.view');
    Route::get('/blocklist', Blocklist::class)->name('blocklist');
    Route::get('/favorites', Favorite::class)->name('favorites');
    Route::get('/requests', Request::class)->name('requests');
});

require __DIR__ . '/auth.php';
