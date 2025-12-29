<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaGateway implements PaymentGatewayInterface
{
    protected string $apiKey;
    protected string $publicKey;
    protected string $serviceProviderCode;
    protected string $baseUrl;
    protected bool $sandbox;

    public function __construct()
    {
        $this->sandbox = config('services.mpesa.sandbox', true);
        $this->apiKey = config('services.mpesa.api_key');
        $this->publicKey = config('services.mpesa.public_key');
        $this->serviceProviderCode = config('services.mpesa.service_provider_code');

        $this->baseUrl = $this->sandbox
            ? 'https://api.sandbox.vm.co.mz'
            : 'https://api.vm.co.mz';
    }

    public function getName(): string
    {
        return 'mpesa';
    }

    /**
     * Generate Bearer Token for API authentication
     */
    protected function generateBearerToken(): string
    {
        $publicKeyFormatted = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($this->publicKey, 64, "\n") .
            "-----END PUBLIC KEY-----";

        openssl_public_encrypt($this->apiKey, $encrypted, $publicKeyFormatted, OPENSSL_PKCS1_PADDING);

        return base64_encode($encrypted);
    }

    /**
     * Initiate C2B (Customer to Business) payment
     */
    public function initiatePayment(Order $order, array $data): array
    {
        try {
            $phone = $this->formatPhoneNumber($data['phone']);
            $reference = 'ORD' . $order->id . time();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->generateBearerToken(),
                'Content-Type' => 'application/json',
                'Origin' => config('app.url'),
            ])->post($this->baseUrl . ':18352/ipg/v1x/c2bPayment/singleStage/', [
                'input_TransactionReference' => $reference,
                'input_CustomerMSISDN' => $phone,
                'input_Amount' => number_format($order->total, 2, '.', ''),
                'input_ThirdPartyReference' => $order->order_number,
                'input_ServiceProviderCode' => $this->serviceProviderCode,
            ]);

            $result = $response->json();

            Log::info('M-Pesa Payment Response', ['order' => $order->id, 'response' => $result]);

            if ($response->successful() && isset($result['output_ResponseCode']) && $result['output_ResponseCode'] === 'INS-0') {
                return [
                    'success' => true,
                    'transaction_id' => $result['output_TransactionID'] ?? null,
                    'conversation_id' => $result['output_ConversationID'] ?? null,
                    'reference' => $reference,
                    'message' => 'Pagamento iniciado. Confirme no seu telefone.',
                    'raw_response' => $result,
                ];
            }

            return [
                'success' => false,
                'error_code' => $result['output_ResponseCode'] ?? 'UNKNOWN',
                'message' => $this->getErrorMessage($result['output_ResponseCode'] ?? 'UNKNOWN'),
                'raw_response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('M-Pesa Payment Error', ['order' => $order->id, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao processar pagamento M-Pesa: ' . $e->getMessage(),
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
                'Authorization' => 'Bearer ' . $this->generateBearerToken(),
                'Content-Type' => 'application/json',
                'Origin' => config('app.url'),
            ])->get($this->baseUrl . ':18353/ipg/v1x/queryTransactionStatus/', [
                'input_QueryReference' => $transactionId,
                'input_ServiceProviderCode' => $this->serviceProviderCode,
            ]);

            $result = $response->json();

            return [
                'success' => $response->successful(),
                'status' => $result['output_ResponseCode'] ?? 'UNKNOWN',
                'message' => $result['output_ResponseDesc'] ?? 'Status desconhecido',
                'raw_response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('M-Pesa Status Check Error', ['transaction' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erro ao verificar status: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handle callback from M-Pesa
     */
    public function handleCallback(array $data): array
    {
        Log::info('M-Pesa Callback Received', $data);

        $transactionId = $data['output_TransactionID'] ?? null;
        $responseCode = $data['output_ResponseCode'] ?? null;

        if ($responseCode === 'INS-0') {
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
            'error' => $this->getErrorMessage($responseCode),
        ];
    }

    /**
     * Refund payment (B2C)
     */
    public function refund(string $transactionId, float $amount): array
    {
        // M-Pesa reversal is complex and requires special permissions
        // For now, return not supported
        return [
            'success' => false,
            'message' => 'Reembolso M-Pesa requer processamento manual.',
        ];
    }

    /**
     * Format phone number to M-Pesa format (258XXXXXXXXX)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove spaces, dashes, and plus sign
        $phone = preg_replace('/[\s\-\+]/', '', $phone);

        // If starts with 0, replace with 258
        if (str_starts_with($phone, '0')) {
            $phone = '258' . substr($phone, 1);
        }

        // If doesn't start with 258, add it
        if (!str_starts_with($phone, '258')) {
            $phone = '258' . $phone;
        }

        return $phone;
    }

    /**
     * Get human-readable error message
     */
    protected function getErrorMessage(string $code): string
    {
        $messages = [
            'INS-0' => 'Transação processada com sucesso',
            'INS-1' => 'Erro interno',
            'INS-2' => 'Token de API inválido',
            'INS-4' => 'Utilizador não autorizado',
            'INS-5' => 'Transação cancelada pelo utilizador',
            'INS-6' => 'Transação falhou',
            'INS-9' => 'Timeout na transação',
            'INS-10' => 'Transação duplicada',
            'INS-13' => 'Shortcode inválido',
            'INS-14' => 'Valor inválido',
            'INS-15' => 'Referência duplicada',
            'INS-17' => 'Código de serviço inválido',
            'INS-18' => 'Número de telefone inválido',
            'INS-20' => 'Tempo limite excedido',
            'INS-21' => 'Parâmetros inválidos',
            'INS-22' => 'Limite de transação excedido',
            'INS-23' => 'Saldo insuficiente',
            'INS-24' => 'PIN inválido',
            'INS-25' => 'Limite de PIN excedido',
            'INS-993' => 'Autenticação falhou',
            'INS-994' => 'Utilizador não autorizado',
            'INS-995' => 'Utilizador suspenso',
            'INS-996' => 'Servidor temporariamente indisponível',
        ];

        return $messages[$code] ?? 'Erro desconhecido (' . $code . ')';
    }
}
