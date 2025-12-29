<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->registerGateways();
    }

    /**
     * Register available payment gateways
     */
    protected function registerGateways(): void
    {
        $this->gateways = [
            'mpesa' => MpesaGateway::class,
            'emola' => EmolaGateway::class,
            'card' => CardGateway::class,
        ];
    }

    /**
     * Get gateway instance
     */
    public function gateway(string $name): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$name])) {
            throw new \InvalidArgumentException("Gateway '{$name}' not found");
        }

        return app($this->gateways[$name]);
    }

    /**
     * Get all available gateways
     */
    public function getAvailableGateways(): array
    {
        $available = [];

        // M-Pesa
        if (config('services.mpesa.api_key')) {
            $available['mpesa'] = [
                'name' => 'M-Pesa',
                'icon' => 'mpesa',
                'description' => 'Pague instantaneamente pelo celular',
                'badge' => 'Rápido',
                'enabled' => true,
            ];
        }

        // e-Mola
        if (config('services.emola.api_key')) {
            $available['emola'] = [
                'name' => 'e-Mola',
                'icon' => 'emola',
                'description' => 'Pagamento móvel Movitel',
                'badge' => null,
                'enabled' => true,
            ];
        }

        // Card (Stripe)
        if (config('services.stripe.key')) {
            $available['card'] = [
                'name' => 'Cartão de Crédito/Débito',
                'icon' => 'card',
                'description' => 'Visa, Mastercard, American Express',
                'badge' => 'Seguro',
                'enabled' => true,
            ];
        }

        // Bank Transfer (always available)
        $available['bank_transfer'] = [
            'name' => 'Transferência Bancária',
            'icon' => 'bank',
            'description' => 'Transfira e envie comprovativo',
            'badge' => null,
            'enabled' => true,
        ];

        return $available;
    }

    /**
     * Process payment for an order
     */
    public function processPayment(Order $order, string $method, array $data): array
    {
        Log::info('Processing payment', [
            'order' => $order->id,
            'method' => $method,
        ]);

        // Bank transfer is manual
        if ($method === 'bank_transfer') {
            return $this->handleBankTransfer($order, $data);
        }

        try {
            $gateway = $this->gateway($method);
            $result = $gateway->initiatePayment($order, $data);

            // Update order with payment info
            if ($result['success']) {
                $order->update([
                    'payment_gateway' => $method,
                    'payment_reference' => $result['reference'] ?? null,
                    'transaction_id' => $result['transaction_id'] ?? null,
                    'payment_response' => $result['raw_response'] ?? null,
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'order' => $order->id,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle bank transfer payment
     */
    protected function handleBankTransfer(Order $order, array $data): array
    {
        $order->update([
            'payment_gateway' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        return [
            'success' => true,
            'message' => 'Pedido criado. Faça a transferência e aguarde confirmação.',
            'requires_proof' => true,
        ];
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(Order $order): array
    {
        if (!$order->payment_gateway || !$order->transaction_id) {
            return [
                'success' => false,
                'message' => 'Informações de pagamento não encontradas',
            ];
        }

        if ($order->payment_gateway === 'bank_transfer') {
            return [
                'success' => true,
                'status' => $order->payment_status,
                'message' => 'Aguardando confirmação manual',
            ];
        }

        try {
            $gateway = $this->gateway($order->payment_gateway);
            return $gateway->checkStatus($order->transaction_id);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid(Order $order, ?string $transactionId = null): void
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
            'transaction_id' => $transactionId ?? $order->transaction_id,
        ]);

        Log::info('Order marked as paid', ['order' => $order->id]);
    }

    /**
     * Mark order as failed
     */
    public function markAsFailed(Order $order, ?string $reason = null): void
    {
        $order->update([
            'payment_status' => 'failed',
            'notes' => $reason ? ($order->notes . "\nFalha: " . $reason) : $order->notes,
        ]);

        Log::info('Order payment failed', ['order' => $order->id, 'reason' => $reason]);
    }

    /**
     * Process refund
     */
    public function refund(Order $order, ?float $amount = null): array
    {
        $amount = $amount ?? $order->total;

        if (!$order->payment_gateway || $order->payment_gateway === 'bank_transfer') {
            return [
                'success' => false,
                'message' => 'Reembolso manual necessário para transferência bancária',
            ];
        }

        if (!$order->transaction_id) {
            return [
                'success' => false,
                'message' => 'ID da transação não encontrado',
            ];
        }

        try {
            $gateway = $this->gateway($order->payment_gateway);
            return $gateway->refund($order->transaction_id, $amount);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao processar reembolso: ' . $e->getMessage(),
            ];
        }
    }
}
