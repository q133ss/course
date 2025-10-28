<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->withCount('videos')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.courses.index', [
            'courses' => $courses,
            'pageTitle' => 'Курсы',
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.create', [
            'pageTitle' => 'Новый курс',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        Course::query()->create($data);

        return redirect()->route('admin.courses.index')->with('status', 'Курс создан.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course,
            'pageTitle' => 'Редактирование курса',
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $this->validatedData($request, $course->id);

        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail && ! Str::startsWith($course->thumbnail, ['http://', 'https://'])) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')->with('status', 'Курс обновлен.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', 'Курс удален.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'start_date' => ['nullable', 'date'],
        ];

        if ($ignoreId) {
            $rules['slug'][] = Rule::unique('courses', 'slug')->ignore($ignoreId);
        } else {
            $rules['slug'][] = Rule::unique('courses', 'slug');
        }

        $data = $request->validate($rules);

        $data['is_free'] = $request->boolean('is_free');
        $data['price'] = $data['is_free'] ? 0 : ($data['price'] ?? 0);
        $data['start_date'] = $data['start_date'] ?? null;

        if (! $request->hasFile('thumbnail')) {
            unset($data['thumbnail']);
        }

        return $data;
    }
}
