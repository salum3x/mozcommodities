<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;

class CardGateway implements PaymentGatewayInterface
{
    protected bool $sandbox;

    public function __construct()
    {
        $this->sandbox = config('services.stripe.sandbox', true);

        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function getName(): string
    {
        return 'card';
    }

    /**
     * Create a PaymentIntent for card payment
     */
    public function initiatePayment(Order $order, array $data): array
    {
        try {
            // Convert MZN to cents (Stripe uses smallest currency unit)
            // Note: MZN is not directly supported by Stripe, you might need to use USD
            // and handle currency conversion on your end
            $amount = (int) ($order->total * 100);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd', // or 'mzn' if supported
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
                'description' => 'Pedido ' . $order->order_number,
                'receipt_email' => $order->customer_email,
            ]);

            Log::info('Stripe PaymentIntent Created', ['order' => $order->id, 'intent' => $paymentIntent->id]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'reference' => $paymentIntent->id,
                'message' => 'PaymentIntent criado',
                'raw_response' => $paymentIntent->toArray(),
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Payment Error', ['order' => $order->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(string $transactionId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($transactionId);

            $statusMap = [
                'succeeded' => 'paid',
                'processing' => 'processing',
                'requires_payment_method' => 'pending',
                'requires_confirmation' => 'pending',
                'requires_action' => 'pending',
                'canceled' => 'failed',
            ];

            return [
                'success' => true,
                'status' => $statusMap[$paymentIntent->status] ?? 'unknown',
                'stripe_status' => $paymentIntent->status,
                'message' => $this->getStatusMessage($paymentIntent->status),
                'raw_response' => $paymentIntent->toArray(),
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Status Check Error', ['transaction' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleCallback(array $data): array
    {
        Log::info('Stripe Webhook Received', ['type' => $data['type'] ?? 'unknown']);

        $event = $data;
        $paymentIntent = $event['data']['object'] ?? null;

        if (!$paymentIntent) {
            return [
                'success' => false,
                'error' => 'Invalid webhook data',
            ];
        }

        $transactionId = $paymentIntent['id'] ?? null;

        switch ($event['type'] ?? '') {
            case 'payment_intent.succeeded':
                return [
                    'success' => true,
                    'transaction_id' => $transactionId,
                    'status' => 'paid',
                ];

            case 'payment_intent.payment_failed':
                return [
                    'success' => false,
                    'transaction_id' => $transactionId,
                    'status' => 'failed',
                    'error' => $paymentIntent['last_payment_error']['message'] ?? 'Payment failed',
                ];

            default:
                return [
                    'success' => false,
                    'transaction_id' => $transactionId,
                    'status' => 'unknown',
                ];
        }
    }

    /**
     * Refund payment
     */
    public function refund(string $transactionId, float $amount): array
    {
        try {
            $refundAmount = (int) ($amount * 100);

            $refund = Refund::create([
                'payment_intent' => $transactionId,
                'amount' => $refundAmount,
            ]);

            Log::info('Stripe Refund Created', ['transaction' => $transactionId, 'refund' => $refund->id]);

            return [
                'success' => $refund->status === 'succeeded',
                'refund_id' => $refund->id,
                'message' => $refund->status === 'succeeded'
                    ? 'Reembolso processado com sucesso'
                    : 'Reembolso em processamento',
            ];

        } catch (\Exception $e) {
            Log::error('Stripe Refund Error', ['transaction' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao processar reembolso: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get human-readable status message
     */
    protected function getStatusMessage(string $status): string
    {
        return match ($status) {
            'succeeded' => 'Pagamento confirmado',
            'processing' => 'Pagamento em processamento',
            'requires_payment_method' => 'Aguardando método de pagamento',
            'requires_confirmation' => 'Aguardando confirmação',
            'requires_action' => 'Requer ação adicional',
            'canceled' => 'Pagamento cancelado',
            default => 'Status desconhecido',
        };
    }
}
