<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseController::class, 'index'])->name('home');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/my', [CourseController::class, 'my'])
    ->middleware('auth')
    ->name('courses.my');

Route::middleware('auth')->group(function () {
    Route::get('/profile', ProfileController::class)->name('profile');
    Route::get('/checkout/{course}', [CheckoutController::class, 'show'])->name('checkout.show');
});
