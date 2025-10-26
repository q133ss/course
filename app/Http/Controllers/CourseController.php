<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->filter($request->only(['search', 'type']))
            ->with(['videos' => fn ($query) => $query->orderBy('sort_order')])
            ->orderBy('created_at', 'desc')
            ->get();

        $user = $request->user();
        $paidCourseIds = [];
        $userPurchases = [];

        if ($user) {
            $paidCourseIds = Purchase::query()
                ->where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->pluck('course_id')
                ->all();

            $userPurchases = array_fill_keys($paidCourseIds, true);
        }

        return view('courses.index', [
            'courses' => $courses,
            'userPurchases' => $userPurchases,
            'paidCourseIds' => $paidCourseIds,
        ]);
    }

    private function userHasAccess(?\App\Models\User $user, Course $course): bool
    {
        if ($course->is_free) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return Purchase::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('payment_status', 'paid')
            ->exists();
    }
}
