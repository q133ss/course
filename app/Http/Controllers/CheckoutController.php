<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Purchase;
use App\Services\Payments\Exceptions\PaymentException;
use App\Services\Payments\YooKassaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function show(Request $request, Course $course): View
    {
        $existingPurchase = null;

        if ($request->user()) {
            $existingPurchase = Purchase::query()
                ->where('user_id', $request->user()->id)
                ->where('course_id', $course->id)
                ->orderByDesc('purchased_at')
                ->first();
        }

        return view('checkout.show', [
            'course' => $course,
            'existingPurchase' => $existingPurchase,
        ]);
    }

    public function process(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ($course->is_free || (float) $course->price <= 0) {
            return redirect()
                ->route('courses.index')
                ->with('status', 'Этот курс не требует оплаты.');
        }

        $latestPurchase = Purchase::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->orderByDesc('purchased_at')
            ->first();

        if ($latestPurchase) {
            if ($latestPurchase->payment_status === 'paid') {
                return redirect()
                    ->route('courses.my')
                    ->with('status', 'Вы уже приобрели этот курс.');
            }

            if ($latestPurchase->payment_status === 'pending' && $latestPurchase->provider_payment_id) {
                return redirect()->route('checkout.status', [
                    'course' => $course,
                    'purchase' => $latestPurchase,
                ]);
            }
        }

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'payment_status' => 'pending',
            'payment_method' => 'yookassa',
            'payment_provider' => 'yookassa',
        ]);

        try {
            /** @var YooKassaService $yooKassa */
            $yooKassa = app(YooKassaService::class);

            $payment = $yooKassa->createPayment(
                $course->price,
                'Оплата курса «' . $course->title . '»',
                route('checkout.status', [
                    'course' => $course,
                    'purchase' => $purchase,
                ], true),
                [
                    'purchase_id' => $purchase->id,
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                ]
            );
        } catch (PaymentException|RequestException $exception) {
            $purchase->delete();
            Log::error('Не удалось создать платеж YooKassa', [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Не удалось создать платеж. Попробуйте еще раз позже.',
            ]);
        }

        $purchase->forceFill([
            'provider_payment_id' => $payment['id'] ?? null,
        ])->save();

        $confirmationUrl = data_get($payment, 'confirmation.confirmation_url')
            ?? data_get($payment, 'confirmation.url');

        if (! $confirmationUrl) {
            $purchase->delete();

            return back()->withErrors([
                'payment' => 'Не удалось получить ссылку на оплату.',
            ]);
        }

        return redirect()->away($confirmationUrl);
    }

    public function status(Request $request, Course $course, Purchase $purchase): View
    {
        $user = $request->user();

        abort_unless($user && $purchase->user_id === $user->id && $purchase->course_id === $course->id, 403);

        if ($purchase->payment_status === 'pending' && $purchase->provider_payment_id) {
            try {
                /** @var YooKassaService $yooKassa */
                $yooKassa = app(YooKassaService::class);
                $payment = $yooKassa->getPayment($purchase->provider_payment_id);

                $status = $payment['status'] ?? null;

                if ($status === 'succeeded' && $purchase->payment_status !== 'paid') {
                    $purchase->forceFill([
                        'payment_status' => 'paid',
                        'purchased_at' => now(),
                    ])->save();
                } elseif ($status === 'canceled' && $purchase->payment_status !== 'failed') {
                    $purchase->forceFill([
                        'payment_status' => 'failed',
                    ])->save();
                }
            } catch (PaymentException|RequestException $exception) {
                Log::warning('Не удалось обновить статус платежа YooKassa', [
                    'purchase_id' => $purchase->id,
                    'error' => $exception->getMessage(),
                ]);
            }

            $purchase->refresh();
        }

        return view('checkout.status', [
            'course' => $course,
            'purchase' => $purchase,
        ]);
    }
}
