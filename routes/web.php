<?php

use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Livewire\UpdateProfile;
use App\Http\Livewire\ViewProfile;
use App\Http\ModelSimilarity\UserSimilarity;
use App\Models\User;
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

Route::get('/test', [UpdateProfileController::class, 'index'])->name('test');


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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', UpdateProfile::class)->withoutMiddleware(['profile.updated'])->name('profile.update');
    // Route::get('/view_profile', ViewProfile::class)->name('profile.view');
    Route::get('/view_profile/{user}', ViewProfile::class)->name('profile.view');

    Route::get('/blocklist', function () {
        $arr_1 = ['a', 'b', 'c'];
        $arr_2 = ['a', 'b', 'c', 'd'];

        function jaccard($arr_1 = [], $arr_2 = [])
        {
            $intersection = array_unique(array_intersect($arr_1, $arr_2));
            $union = array_unique(array_merge($arr_1, $arr_2));

            return count($intersection) / count($union);
        }

       

        function OVRS($arr_1 = [], $arr_2 = [])
        {
            //if both arrays are identical return one 1
            if ($arr_1 === $arr_2) {
                return 1;
            }

            //else destructure the arrays into the four needed variables
            list($min_1, $max_1) = $arr_1;
            list($min_2, $max_2) = $arr_2;

            // if the arrays donot oerlap return 0
            if (!(($max_2 >= $min_1) && ($min_2 <= $max_1))) {
                return 0;
            }

            $arr_1_range = range( $arr_1[0], $arr_1[1], env('BUDGET_PRICE_STEP', 20000));
            $arr_2_range = range( $arr_2[0], $arr_2[1], env('BUDGET_PRICE_STEP', 20000));

            return [OVRS_kernel($arr_1_range, $arr_2_range), jaccard($arr_1_range, $arr_2_range)];
        }

        function OVRS_kernel($arr_1_range = [], $arr_2_range)
        {

            $intersection = array_unique(array_intersect($arr_1_range, $arr_2_range));
            $arr_1_diff = count(array_diff($arr_1_range, $intersection));
            $arr_2_diff = count(array_diff($arr_2_range, $intersection));
            $_intersection = count($intersection);

            if ($arr_1_diff === 0) {
                $ovrs = $_intersection/($arr_2_diff + $_intersection);
            } else if ($arr_2_diff === 0) {
                $ovrs = $_intersection/($arr_1_diff + $_intersection);
            }
            else {
                $ovrs = $_intersection/(min($arr_2_diff, $arr_1_diff) + $_intersection);                
            }
            
            return $ovrs;
        }

        $arr_3 = [100000, 120000];
        $arr_4 = [40000, 180000];

        function simple_Diff_Sim(int $course_level_1, int $course_level_2, $min_course_level = 100, $max_course_level = 700)
        {
            return [1-(abs($course_level_1 - $course_level_2)/($max_course_level - $min_course_level))];
        }

        // dd(OVRS($arr_4, $arr_3), simple_Diff_Sim(100, 600));
        
        dd((new UserSimilarity(auth()->user()))->calculateUsersSimilarityScore(User::all()));


        return jaccard($arr_1, $arr_2);

    })->name('blocklist');
});

require __DIR__ . '/auth.php';
