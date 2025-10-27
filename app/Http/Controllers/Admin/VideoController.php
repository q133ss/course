<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $data = $this->validatedData($request, $video);

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('status', 'Видео обновлено.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $this->deleteStoredFile($video->getRawOriginal('video_url'));
        $this->deleteStoredFile($video->getRawOriginal('preview_image'));

        $video->delete();

        return redirect()->route('admin.videos.index')->with('status', 'Видео удалено.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Video $video = null): array
    {
        $rules = [
            'course_id' => ['required', Rule::exists('courses', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'full_description' => ['nullable', 'string'],
            'video_file' => [$video ? 'nullable' : 'required', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm,video/mpeg', 'max:512000'],
            'preview_image_file' => ['nullable', 'image', 'max:10240'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
            'is_free' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules);

        $data = [
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'full_description' => $validated['full_description'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'sort_order' => $validated['sort_order'] ?? null,
            'is_free' => $request->boolean('is_free'),
        ];

        if ($request->hasFile('video_file')) {
            if ($video) {
                $this->deleteStoredFile($video->getRawOriginal('video_url'));
            }

            $path = $request->file('video_file')->store('videos', 'public');
            $data['video_url'] = $path;
        } elseif ($video) {
            $data['video_url'] = $video->getRawOriginal('video_url');
        }

        if ($request->hasFile('preview_image_file')) {
            if ($video && $video->getRawOriginal('preview_image')) {
                $this->deleteStoredFile($video->getRawOriginal('preview_image'));
            }

            $previewPath = $request->file('preview_image_file')->store('video-previews', 'public');
            $data['preview_image'] = $previewPath;
        } elseif ($video) {
            $data['preview_image'] = $video->getRawOriginal('preview_image');
        }

        return $data;
    }

    private function deleteStoredFile(?string $path): void
    {
        if (!$path || $this->isExternalFile($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function isExternalFile(string $path): bool
    {
        return Str::startsWith($path, ['http://', 'https://', '//', '/']);
    }
}
