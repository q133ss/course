<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePreorder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoursePreorderController extends Controller
{
    public function store(Request $request, Course $course): JsonResponse
    {
        if (! $course->isUpcoming()) {
            return response()->json([
                'message' => 'Курс уже доступен для просмотра.',
            ], 422);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $contact = trim((string) $validated['contact']);
        $name = array_key_exists('name', $validated) ? trim((string) $validated['name']) : null;

        if ($name === '') {
            $name = null;
        }

        if ($user) {
            $name = $name ?: $user->name;
        }

        $preorder = CoursePreorder::query()->updateOrCreate(
            [
                'course_id' => $course->id,
                'contact' => $contact,
            ],
            [
                'user_id' => $user?->id,
                'name' => $name,
            ]
        );

        return response()->json([
            'message' => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время!',
            'preorder_id' => $preorder->id,
        ]);
    }
}
