<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request, Course $course): View
    {
        $request->user() ?? abort(403);

        return view('checkout.show', [
            'course' => $course->loadCount('videos'),
        ]);
    }
}
