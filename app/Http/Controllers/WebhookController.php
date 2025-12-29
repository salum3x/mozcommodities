<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle M-Pesa webhook
     */
    public function mpesa(Request $request)
    {
        Log::info('M-Pesa Webhook', $request->all());

        try {
            $gateway = $this->paymentService->gateway('mpesa');
            $result = $gateway->handleCallback($request->all());

            if ($result['success'] && isset($result['transaction_id'])) {
                $order = Order::where('transaction_id', $result['transaction_id'])
                    ->orWhere('payment_reference', $result['transaction_id'])
                    ->first();

                if ($order) {
                    $this->paymentService->markAsPaid($order, $result['transaction_id']);
                }
            }

            return response()->json(['status' => 'received']);

        } catch (\Exception $e) {
            Log::error('M-Pesa Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle e-Mola webhook
     */
    public function emola(Request $request)
    {
        Log::info('e-Mola Webhook', $request->all());

        try {
            $gateway = $this->paymentService->gateway('emola');
            $result = $gateway->handleCallback($request->all());

            if ($result['success'] && isset($result['transaction_id'])) {
                $order = Order::where('transaction_id', $result['transaction_id'])
                    ->orWhere('payment_reference', $result['transaction_id'])
                    ->first();

                if ($order) {
                    $this->paymentService->markAsPaid($order, $result['transaction_id']);
                }
            }

            return response()->json(['status' => 'received']);

        } catch (\Exception $e) {
            Log::error('e-Mola Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function stripe(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature if secret is configured
            if ($webhookSecret) {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } else {
                $event = json_decode($payload, true);
            }

            Log::info('Stripe Webhook', ['type' => $event['type'] ?? 'unknown']);

            $gateway = $this->paymentService->gateway('card');
            $result = $gateway->handleCallback(is_array($event) ? $event : $event->toArray());

            if ($result['success'] && $result['status'] === 'paid' && isset($result['transaction_id'])) {
                $order = Order::where('transaction_id', $result['transaction_id'])
                    ->orWhere('payment_reference', $result['transaction_id'])
                    ->first();

                if ($order) {
                    $this->paymentService->markAsPaid($order, $result['transaction_id']);
                }
            } elseif (!$result['success'] && $result['status'] === 'failed') {
                $order = Order::where('transaction_id', $result['transaction_id'] ?? '')
                    ->orWhere('payment_reference', $result['transaction_id'] ?? '')
                    ->first();

                if ($order) {
                    $this->paymentService->markAsFailed($order, $result['error'] ?? 'Payment failed');
                }
            }

            return response()->json(['status' => 'received']);

        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Invalid Payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Invalid Signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
    }
}
