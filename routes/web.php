<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QuestionController;


Route::group(['middleware' => ['auth', 'check_roles']], static function () {

    Route::get('/', static fn() => redirect()->route(auth()->user()?->getRedirectRoute()))->name('index');
    Route::get('/dashboard', HomeController::class)->middleware('is_admin')->name('dashboard');

    // This route is not protected by the 'check_roles' middleware
    Route::withoutMiddleware(['check_roles'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
        Route::post('/profile/school', [ProfileController::class, 'updateSchool'])->name('user.profile.school');
    });

    /**
     * Quiz Controller
     */
    route::get("quiz", [QuizController::class, 'index'])->name('quiz.index');
    route::post("quiz", [QuizController::class, 'show'])->name('quiz.show');

    /**
     * Result Controller
     */

    route::resource("results", ResultController::class)->only(['index', 'show', 'store']);
});
Route::group(['middleware' => ['auth', 'verified', 'is_admin']], static function () {
    /**
     * Admin Group Controller
     *
     */

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], static function () {

        /**
         * Question Controller
         */
        Route::get('questions/trash', [QuestionController::class, 'trash'])
            ->name('questions.trash')->withTrashed();
        Route::put('questions/{question}/restore', [QuestionController::class, 'restore'])
            ->name('questions.restore')->withTrashed();
        Route::delete('questions/{question}', [QuestionController::class, 'forceDelete'])
            ->name('questions.delete')->withTrashed();
        Route::resource("questions", QuestionController::class)->withTrashed(['index', 'show', 'edit', 'destroy']);
        /**
         * Topics Controller
         */
        Route::resource("topics", TopicController::class);

        /**
         * School Management Controller
         */
        route::resource("schools", SchoolController::class)->withTrashed(['index', 'show', 'edit', 'update', 'destroy']);

        /**
         * User Management Controller
         */
        Route::put('users/{user}/restore', [UserController::class, 'restore'])
            ->name('users.restore')->withTrashed();
        route::resource("users", UserController::class)->withTrashed(['index', 'show', 'edit', 'destroy']);

        route::resource("students", StudentController::class)->parameters([
            'students' => 'user'
        ])->only(['index', 'create', 'show', 'edit'])->withTrashed(['index', 'show', 'edit']);
        Route::resource('teachers', TeacherController::class)->parameters([
            'teachers' => 'user'
        ])->only(['index', 'create', 'show', 'edit'])->withTrashed(['index', 'show', 'edit']);
    });
});


require __DIR__ . '/auth.php';
