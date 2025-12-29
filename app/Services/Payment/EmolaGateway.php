<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmolaGateway implements PaymentGatewayInterface
{
    protected string $merchantId;
    protected string $apiKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected bool $sandbox;

    public function __construct()
    {
        $this->sandbox = config('services.emola.sandbox', true);
        $this->merchantId = config('services.emola.merchant_id');
        $this->apiKey = config('services.emola.api_key');
        $this->secretKey = config('services.emola.secret_key');

        $this->baseUrl = $this->sandbox
            ? 'https://sandbox.emola.co.mz/api/v1'
            : 'https://api.emola.co.mz/api/v1';
    }

    public function getName(): string
    {
        return 'emola';
    }

    /**
     * Generate signature for API request
     */
    protected function generateSignature(array $data): string
    {
        ksort($data);
        $signString = implode('', array_values($data)) . $this->secretKey;
        return hash('sha256', $signString);
    }

    /**
     * Initiate e-Mola payment
     */
    public function initiatePayment(Order $order, array $data): array
    {
        try {
            $phone = $this->formatPhoneNumber($data['phone']);
            $reference = 'EMO' . $order->id . time();

            $payload = [
                'merchant_id' => $this->merchantId,
                'amount' => number_format($order->total, 2, '.', ''),
                'currency' => 'MZN',
                'phone' => $phone,
                'reference' => $reference,
                'description' => 'Pedido ' . $order->order_number,
                'callback_url' => route('webhooks.emola'),
            ];

            $payload['signature'] = $this->generateSignature($payload);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/initiate', $payload);

            $result = $response->json();

            Log::info('e-Mola Payment Response', ['order' => $order->id, 'response' => $result]);

            if ($response->successful() && ($result['status'] ?? '') === 'pending') {
                return [
                    'success' => true,
                    'transaction_id' => $result['transaction_id'] ?? null,
                    'reference' => $reference,
                    'message' => 'Pagamento iniciado. Confirme no seu telefone.',
                    'raw_response' => $result,
                ];
            }

            return [
                'success' => false,
                'error_code' => $result['error_code'] ?? 'UNKNOWN',
                'message' => $result['message'] ?? 'Erro ao processar pagamento e-Mola',
                'raw_response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('e-Mola Payment Error', ['order' => $order->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao processar pagamento e-Mola: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/payments/status/' . $transactionId);

            $result = $response->json();

            return [
                'success' => $response->successful(),
                'status' => $result['status'] ?? 'unknown',
                'message' => $result['message'] ?? 'Status desconhecido',
                'raw_response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('e-Mola Status Check Error', ['transaction' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle callback from e-Mola
     */
    public function handleCallback(array $data): array
    {
        Log::info('e-Mola Callback Received', $data);

        // Verify signature
        $receivedSignature = $data['signature'] ?? '';
        unset($data['signature']);
        $expectedSignature = $this->generateSignature($data);

        if (!hash_equals($expectedSignature, $receivedSignature)) {
            Log::warning('e-Mola Callback: Invalid signature');
            return [
                'success' => false,
                'error' => 'Invalid signature',
            ];
        }

        $transactionId = $data['transaction_id'] ?? null;
        $status = $data['status'] ?? null;

        if ($status === 'completed' || $status === 'paid') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'status' => 'paid',
            ];
        }

        return [
            'success' => false,
            'transaction_id' => $transactionId,
            'status' => 'failed',
            'error' => $data['message'] ?? 'Payment failed',
        ];
    }

    /**
     * Refund payment
     */
    public function refund(string $transactionId, float $amount): array
    {
        try {
            $payload = [
                'merchant_id' => $this->merchantId,
                'transaction_id' => $transactionId,
                'amount' => number_format($amount, 2, '.', ''),
            ];

            $payload['signature'] = $this->generateSignature($payload);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments/refund', $payload);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? '') === 'refunded') {
                return [
                    'success' => true,
                    'message' => 'Reembolso processado com sucesso',
                ];
            }

            return [
                'success' => false,
                'message' => $result['message'] ?? 'Erro ao processar reembolso',
            ];

        } catch (\Exception $e) {
            Log::error('e-Mola Refund Error', ['transaction' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao processar reembolso: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to e-Mola format (258XXXXXXXXX)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[\s\-\+]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '258' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '258')) {
            $phone = '258' . $phone;
        }

        return $phone;
    }
}
