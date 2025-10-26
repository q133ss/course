<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Purchase;
use App\Models\UserProgress;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $paidCourseIds = Purchase::query()
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->pluck('course_id')
            ->all();

        $courses = Course::query()
            ->with(['videos' => fn ($query) => $query->orderBy('sort_order')])
            ->where(function (Builder $query) use ($paidCourseIds) {
                $query->where('is_free', true);

                if ($paidCourseIds !== []) {
                    $query->orWhereIn('id', $paidCourseIds);
                }
            })
            ->orderBy('title')
            ->get();

        $videoProgress = UserProgress::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('video_id');

        $userPurchases = Purchase::query()
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->get()
            ->keyBy('course_id');

        $courseSummaries = [];
        $statusCounts = [
            'completed' => 0,
            'in_progress' => 0,
            'not_started' => 0,
        ];
        $latestActivityAt = null;
        $overallProgressAccumulator = 0;

        foreach ($courses as $course) {
            $videos = $course->videos;
            $totalVideos = $videos->count();
            $sumProgress = 0;
            $completedVideos = 0;
            $lastWatchedAt = null;
            $nextVideo = null;

            foreach ($videos as $video) {
                $progress = $videoProgress->get($video->id);
                $percent = $progress?->progress_percent ?? 0;
                $percent = max(0, min(100, (int) $percent));

                $sumProgress += $percent;

                if ($percent >= 90) {
                    $completedVideos++;
                } elseif ($nextVideo === null) {
                    $nextVideo = $video;
                }

                if ($progress?->last_watched_at) {
                    if ($lastWatchedAt === null || $progress->last_watched_at->gt($lastWatchedAt)) {
                        $lastWatchedAt = $progress->last_watched_at;
                    }
                }
            }

            if ($nextVideo === null && $totalVideos > 0) {
                $nextVideo = $videos->first();
            }

            $averageProgress = $totalVideos > 0
                ? (int) round($sumProgress / $totalVideos)
                : 0;
            $averageProgress = max(0, min(100, $averageProgress));

            $status = match (true) {
                $totalVideos > 0 && $completedVideos === $totalVideos => 'completed',
                $averageProgress > 0 => 'in_progress',
                default => 'not_started',
            };

            $courseSummaries[$course->id] = [
                'progress_percent' => $averageProgress,
                'completed_videos' => $completedVideos,
                'total_videos' => $totalVideos,
                'status' => $status,
                'last_watched_at' => $lastWatchedAt,
                'next_video' => $nextVideo,
            ];

            $statusCounts[$status]++;
            $overallProgressAccumulator += $averageProgress;

            if ($lastWatchedAt !== null) {
                if ($latestActivityAt === null || $lastWatchedAt->gt($latestActivityAt)) {
                    $latestActivityAt = $lastWatchedAt;
                }
            }
        }

        $coursesCount = $courses->count();
        $overallProgressPercent = $coursesCount > 0
            ? (int) round($overallProgressAccumulator / $coursesCount)
            : 0;

        return view('courses.my', [
            'title' => 'Мои курсы',
            'courses' => $courses,
            'courseSummaries' => $courseSummaries,
            'statusCounts' => $statusCounts,
            'overallProgressPercent' => $overallProgressPercent,
            'latestActivityAt' => $latestActivityAt,
            'userPurchases' => $userPurchases,
            'videoProgress' => $videoProgress,
        ]);
    }
}
