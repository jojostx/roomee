<?php

use App\Http\Controllers\InertiaController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\TermController;
use Illuminate\Support\Facades\Auth;
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
Route::get('/', function () {
    return view('pages.welcome');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

// Route::get('/contact-us', function () {
//     return view('pages.contact');
// })->name('contact');

Route::get('/contact-us', [ContactsController::class, 'index'])->name('contact');
Route::post('/contact-us', [ContactsController::class, 'store'])->middleware('throttle:60, 1');

Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs');
Route::post('/faqs', [FaqsController::class, 'store'])->middleware('throttle:60, 1');

Route::get('/features', function () {
    return view('pages.features');
})->name('features');

Route::get('/privacy', function () {
    return view('pages.privacy-policy');
})->name('privacy');

Route::get('/rules-and-guidelines', function () {
    return view('pages.rules-and-guidelines');
})->name('rules');

// Route::get('/terms', function () {
//     return view('pages.terms');
// })->name('terms');

Route::get('/terms-of-use', [TermController::class, 'show'])->name('terms');



Route::get('/logout', function () {
    Auth::logout();
    return view('pages.welcome');
})->name('logout');



Route::get('/pages', [InertiaController::class, 'show']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
