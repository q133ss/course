<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalUsers = User::query()->count();
        $totalCourses = Course::query()->count();
        $totalRevenue = Purchase::query()->sum('amount');
        $totalPurchases = Purchase::query()->count();

        $currentMonthRange = [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ];

        $monthlyRevenue = Purchase::query()
            ->whereBetween('purchased_at', $currentMonthRange)
            ->sum('amount');

        $recentPurchases = Purchase::query()
            ->with(['user', 'course'])
            ->orderByDesc('purchased_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalRevenue' => $totalRevenue,
            'totalPurchases' => $totalPurchases,
            'monthlyRevenue' => $monthlyRevenue,
            'recentPurchases' => $recentPurchases,
            'pageTitle' => 'Обзор',
        ]);
    }
}
