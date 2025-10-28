<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class YooKassaWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $shopId = (string) config('services.yookassa.shop_id');
        $secretKey = (string) config('services.yookassa.secret_key');

        if ($shopId === '' || $secretKey === '') {
            Log::warning('YooKassa webhook received but credentials are missing.');

            return response()->noContent();
        }

        if ($request->getUser() !== $shopId || $request->getPassword() !== $secretKey) {
            Log::warning('YooKassa webhook authorization failed.', [
                'provided_user' => $request->getUser(),
            ]);

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $paymentData = $request->input('object');

        if (! is_array($paymentData)) {
            Log::warning('YooKassa webhook missing payment object.', [
                'payload' => $request->all(),
            ]);

            return response()->noContent();
        }

        $paymentId = $paymentData['id'] ?? null;

        if (! $paymentId) {
            Log::warning('YooKassa webhook without payment id.', [
                'payload' => $paymentData,
            ]);

            return response()->noContent();
        }

        $purchase = Purchase::query()
            ->where('provider_payment_id', $paymentId)
            ->first();

        if (! $purchase) {
            Log::warning('YooKassa webhook payment not found.', [
                'payment_id' => $paymentId,
            ]);

            return response()->noContent();
        }

        $status = $paymentData['status'] ?? null;

        if ($status === 'succeeded' && $purchase->payment_status !== 'paid') {
            $purchase->forceFill([
                'payment_status' => 'paid',
                'purchased_at' => now(),
            ])->save();
        } elseif ($status === 'canceled' && $purchase->payment_status !== 'failed') {
            $purchase->forceFill([
                'payment_status' => 'failed',
            ])->save();
        } else {
            Log::info('YooKassa webhook received.', [
                'payment_id' => $paymentId,
                'status' => $status,
            ]);
        }

        return response()->noContent();
    }
}
