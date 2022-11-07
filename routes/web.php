<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Admin\QuestionController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/', function () {
    return Inertia::render('HomeView');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('HomeView');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/profile', function () {
    // sleep(3);
    return Inertia::render('Profile');
})->middleware(['auth', 'verified'])->name('profile');


/**O
 * Admin Group Controller
 *
 */

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified'], 'as' => 'admin.'], function () {

    /**
     * Question Controller
     */
    Route::get('questions/trash', [QuestionController::class, 'trash'])
        ->name('questions.trash')->withTrashed();
    Route::put('questions/{question}/restore', [QuestionController::class, 'restore'])
        ->name('questions.restore')->withTrashed();
    Route::delete('questions/{question}', [QuestionController::class, 'forceDelete'])
        ->name('questions.delete')->withTrashed();
    route::resource("questions", QuestionController::class)->withTrashed(['index','show','edit','destroy']);
    /**
     * Topics Controller
     */
    route::resource("questions", QuestionController::class)->withTrashed(['index','show','edit','destroy']);
});

require __DIR__ . '/auth.php';
