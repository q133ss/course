<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Video::query()
            ->with('course')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.videos.index', [
            'videos' => $videos,
            'pageTitle' => 'Видео',
        ]);
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.videos.create', [
            'courses' => $courses,
            'pageTitle' => 'Новое видео',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Video::query()->create($data);

        return redirect()->route('admin.videos.index')->with('status', 'Видео создано.');
    }

    public function edit(Video $video): View
    {
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.videos.edit', [
            'video' => $video,
            'courses' => $courses,
            'pageTitle' => 'Редактирование видео',
        ]);
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $data = $this->validatedData($request);

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('status', 'Видео обновлено.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('admin.videos.index')->with('status', 'Видео удалено.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        $rules = [
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'full_description' => ['nullable', 'string'],
            'video_url' => ['required', 'string', 'max:255'],
            'preview_image' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
            'is_free' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules);

        $validated['is_free'] = $request->boolean('is_free');

        return $validated;
    }
}
