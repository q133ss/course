<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;

class CheckoutController extends Controller
{
    public function show(Course $course): View
    {
        return view('checkout.show', [
            'course' => $course,
        ]);
    }
}
