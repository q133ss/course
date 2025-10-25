<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('courses.index');
});

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/checkout/{course}', [CheckoutController::class, 'show'])->name('checkout.show');

Route::post('/login', function (Request $request) {
    return back()->with('status', 'Авторизация не настроена в демо-версии.');
})->name('login');
