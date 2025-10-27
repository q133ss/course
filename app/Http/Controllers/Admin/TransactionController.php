<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'method' => ['nullable', 'string', 'max:255'],
        ]);

        $query = Purchase::query()
            ->with(['user', 'course'])
            ->orderByDesc('purchased_at');

        if (! empty($filters['status'])) {
            $query->where('payment_status', $filters['status']);
        }

        if (! empty($filters['method'])) {
            $query->where('payment_method', $filters['method']);
        }

        if (! empty($filters['search'])) {
            $search = '%' . str($filters['search'])->lower() . '%';

            $query->where(function ($builder) use ($search) {
                $builder->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->whereRaw('LOWER(name) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$search]);
                })->orWhereHas('course', function ($courseQuery) use ($search) {
                    $courseQuery->whereRaw('LOWER(title) LIKE ?', [$search]);
                });
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        $stats = [
            'totalRevenue' => Purchase::query()->sum('amount'),
            'successfulPayments' => Purchase::query()->where('payment_status', 'successful')->count(),
            'failedPayments' => Purchase::query()->where('payment_status', 'failed')->count(),
        ];

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'stats' => $stats,
            'filters' => $filters,
            'pageTitle' => 'Транзакции',
        ]);
    }
}
