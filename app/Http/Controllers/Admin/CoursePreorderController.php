<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoursePreorder;
use Illuminate\Contracts\View\View;

class CoursePreorderController extends Controller
{
    public function index(): View
    {
        $preorders = CoursePreorder::query()
            ->with(['course', 'user'])
            ->latest()
            ->paginate(20);

        $totalPreorders = $preorders->total();
        $uniqueCoursesCount = CoursePreorder::query()->distinct('course_id')->count('course_id');

        return view('admin.preorders.index', [
            'preorders' => $preorders,
            'totalPreorders' => $totalPreorders,
            'uniqueCoursesCount' => $uniqueCoursesCount,
        ]);
    }
}
