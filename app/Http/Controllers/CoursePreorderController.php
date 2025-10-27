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

        if ($user) {
            $preorder = CoursePreorder::query()
                ->where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->first();

            if (! $preorder) {
                $preorder = CoursePreorder::query()
                    ->where('course_id', $course->id)
                    ->where('contact', $contact)
                    ->whereNull('user_id')
                    ->first();
            }
        } else {
            $preorder = CoursePreorder::query()
                ->where('course_id', $course->id)
                ->where('contact', $contact)
                ->first();
        }

        if (! $preorder) {
            $preorder = new CoursePreorder([
                'course_id' => $course->id,
            ]);
        }

        $contactIsTaken = CoursePreorder::query()
            ->where('course_id', $course->id)
            ->where('contact', $contact)
            ->when($preorder->exists, fn ($query) => $query->where('id', '!=', $preorder->id))
            ->exists();

        if ($contactIsTaken) {
            $message = 'Эти контактные данные уже использованы для заявки на этот курс.';

            return response()->json([
                'message' => $message,
                'errors' => [
                    'contact' => [$message],
                ],
            ], 422);
        }

        $preorder->contact = $contact;
        $preorder->name = $name;

        if ($user) {
            $preorder->user_id = $user->id;
        } elseif (! $preorder->exists) {
            $preorder->user_id = null;
        }

        $preorder->save();

        $message = $preorder->wasRecentlyCreated
            ? 'Заявка отправлена. Мы свяжемся с вами в ближайшее время!'
            : 'Данные заявки обновлены. Мы свяжемся с вами в ближайшее время!';

        return response()->json([
            'message' => $message,
            'preorder_id' => $preorder->id,
        ]);
    }
}
