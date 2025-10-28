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
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15);

        $sortLimits = Course::query()
            ->selectRaw('MIN(sort_order) as min_order, MAX(sort_order) as max_order')
            ->first();

        return view('admin.courses.index', [
            'courses' => $courses,
            'pageTitle' => 'Курсы',
            'minSortOrder' => (int) ($sortLimits->min_order ?? 0),
            'maxSortOrder' => (int) ($sortLimits->max_order ?? 0),
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

    public function move(Request $request, Course $course, string $direction): RedirectResponse
    {
        abort_unless(in_array($direction, ['up', 'down'], true), 404);

        $neighbor = $direction === 'up'
            ? Course::query()
                ->where('sort_order', '<', $course->sort_order)
                ->orderByDesc('sort_order')
                ->orderByDesc('id')
                ->first()
            : Course::query()
                ->where('sort_order', '>', $course->sort_order)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

        if ($neighbor) {
            $currentOrder = $course->sort_order;

            $course->update(['sort_order' => $neighbor->sort_order]);
            $neighbor->update(['sort_order' => $currentOrder]);
        }

        return redirect()
            ->route('admin.courses.index', $request->only('page'))
            ->with('status', 'Порядок курсов обновлён.');
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
