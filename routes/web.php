<?php

use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Livewire\UpdateProfile;
use App\Http\Livewire\ViewProfile;
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

Route::view('/', 'pages.welcome')->name('home');
Route::view('/about','pages.about')->name('about');
Route::view('/features','pages.features')->name('features');
Route::view('/privacy', 'pages.privacy-policy')->name('privacy');
Route::view('/rules-and-guidelines','pages.rules-and-guidelines')->name('rules');
Route::view('/terms-of-use', 'pages.terms')->name('terms');
Route::get('/contact-us', [ContactsController::class, 'index'])->name('contact');
 Route::get('/test', [UpdateProfileController::class, 'index'])->name('test');
Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs');
Route::middleware(['throttle:xhrFormRequest'])->group(function () {
    Route::post('/contact-us', [ContactsController::class, 'store']);
    Route::post('/faqs', [FaqsController::class, 'store']);
});


Route::middleware(['auth:sanctum', 'verified', 'profile.updated'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', UpdateProfile::class)->withoutMiddleware(['profile.updated'])->name('profile.update');
    Route::get('/view_profile', ViewProfile::class)->name('profile.view');
});



require __DIR__ . '/auth.php';
