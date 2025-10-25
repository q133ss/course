<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Purchase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display the full course catalog.
     */
    public function index(Request $request): View
    {
        $courses = $this->paginateCourses();

        return view('courses.index', [
            'courses' => $courses,
            'accessibleCourseIds' => $this->resolveAccessibleCourseIds(),
        ]);
    }

    /**
     * Display only the courses accessible to the authenticated user.
     */
    public function my(Request $request): View
    {
        $request->user() ?? abort(403);

        $accessibleCourseIds = $this->resolveAccessibleCourseIds();

        $courses = $this->paginateCourses($accessibleCourseIds, onlyAccessible: true);

        return view('courses.index', [
            'courses' => $courses,
            'accessibleCourseIds' => $accessibleCourseIds,
        ]);
    }

    /**
     * @param  array<int, int>|null  $courseIds
     */
    protected function paginateCourses(?array $courseIds = null, bool $onlyAccessible = false): LengthAwarePaginator
    {
        $query = Course::query()
            ->with(['videos' => fn ($builder) => $builder->orderBy('sort_order')])
            ->withCount('videos')
            ->orderByDesc('created_at');

        if ($onlyAccessible) {
            $query->where(function ($builder) use ($courseIds) {
                $builder->where('is_free', true);

                if (! empty($courseIds)) {
                    $builder->orWhereIn('id', $courseIds);
                }
            });
        } elseif (! empty($courseIds)) {
            $query->whereIn('id', $courseIds);
        }

        return $query->paginate(9)->withQueryString();
    }

    /**
     * @return array<int, int>
     */
    protected function resolveAccessibleCourseIds(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        return Purchase::query()
            ->where('user_id', $user->getKey())
            ->pluck('course_id')
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }
}
