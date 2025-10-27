<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CoursePreorderController as AdminCoursePreorderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePreorderController;
use App\Http\Controllers\MyCoursesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('courses.index');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/checkout/{course}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/courses/{course}/preorders', [CoursePreorderController::class, 'store'])->name('courses.preorders.store');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/my-courses', MyCoursesController::class)->name('courses.my');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class)->except('show');
        Route::resource('courses', AdminCourseController::class)->except('show');
        Route::get('preorders', [AdminCoursePreorderController::class, 'index'])->name('preorders.index');
        Route::resource('videos', AdminVideoController::class)->except('show');
        Route::resource('roles', RoleController::class)->except('show');

        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
