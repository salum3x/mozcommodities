<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * e-Mola (Movitel) gateway — protocolo iTcore.
 * Endpoint: tv.itcore.co.za/emola/file.php (ou custom configurado em emola_base_url).
 */
class EmolaGateway implements PaymentGatewayInterface
{
    protected ?string $apiKey;
    protected ?string $username;
    protected ?string $password;
    protected ?string $partnerCode;
    protected string $endpoint;
    protected bool $sandbox;

    public function __construct()
    {
        $this->sandbox     = (bool) Setting::get('emola_sandbox', true);
        $this->apiKey      = Setting::get('emola_api_key');
        $this->username    = Setting::get('emola_username');
        $this->password    = Setting::get('emola_password');
        $this->partnerCode = Setting::get('emola_partner_code');

        $configured = Setting::get('emola_base_url');
        $this->endpoint = $configured ?: 'http://tv.itcore.co.za/emola/file.php';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->username) && !empty($this->password) && !empty($this->partnerCode);
    }

    public function getName(): string
    {
        return 'emola';
    }

    /**
     * Validate Movitel e-Mola number — accepts +258, 258, or local format starting with 86/87.
     * Returns the normalized 9-digit MSISDN (e.g. "861234567") or null when invalid.
     */
    protected function normalisePhone(string $phone): ?string
    {
        $clean = preg_replace('/[^\d]/', '', $phone);
        // Strip leading 258 (country code) or 00258
        if (str_starts_with($clean, '00258')) $clean = substr($clean, 5);
        elseif (str_starts_with($clean, '258')) $clean = substr($clean, 3);

        if (preg_match('/^(86|87)\d{7}$/', $clean)) {
            return $clean;
        }
        return null;
    }

    public function initiatePayment(Order $order, array $data): array
    {
        if (!$this->isConfigured()) {
            Log::error('e-Mola não configurado.');
            return [
                'success' => false,
                'message' => 'O pagamento por e-Mola não está configurado. Pede ao administrador para configurar as credenciais no painel admin.',
                'raw_response' => null,
            ];
        }

        $msisdn = $this->normalisePhone($data['phone'] ?? '');
        if (!$msisdn) {
            return [
                'success' => false,
                'message' => 'Número e-Mola inválido. Usa um número Movitel (86 ou 87) no formato +258 86 1234567.',
                'raw_response' => null,
            ];
        }

        // Transaction prefix "MZC" marks transactions as belonging to MozCommodities.
        $transPrefix = 'MZC';
        $transId = strtoupper(substr($transPrefix . 'I' . $order->id . 'I' . bin2hex(random_bytes(2)), 0, 22));
        $refPay  = $transPrefix . $order->id;

        // Show the merchant name in the e-Mola SMS so the customer sees who they're paying.
        $siteTitle = Setting::get('site_name', config('app.name', 'MozCommodities'));
        $sms = "{$siteTitle}: pedido #{$order->order_number}, total " . number_format($order->total, 2, '.', '') . ' MZN. Introduz o PIN para confirmar.';

        $payload = [
            'apiKey'      => $this->apiKey,
            'username'    => $this->username,
            'password'    => $this->password,
            'partnerCode' => $this->partnerCode,
            'transAmount' => number_format($order->total, 2, '.', ''),
            'language'    => 'pt',
            'msidnPhone'  => $msisdn,
            'smscontent'  => mb_substr($sms, 0, 160),
            'refPay'      => $refPay,
            'transId'     => $transId,
        ];

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->asJson()
                ->post($this->endpoint, $payload);

            $result = $response->json();
            Log::info('e-Mola Response', ['order' => $order->id, 'response' => $result]);

            $code = strtolower((string) ($result['output_ResponseCode'] ?? ''));
            if ($response->successful() && $code === 'successfully') {
                return [
                    'success'        => true,
                    'transaction_id' => $result['output_TransactionID'] ?? $transId,
                    'reference'      => $refPay,
                    'message'        => 'Pagamento iniciado. Confirme no seu telefone com o PIN e-Mola.',
                    'raw_response'   => $result,
                ];
            }

            return [
                'success'      => false,
                'error_code'   => $result['output_ResponseCode'] ?? 'UNKNOWN',
                'message'      => $result['output_ResponseDesc'] ?? 'Pagamento e-Mola recusado.',
                'raw_response' => $result,
            ];

        } catch (\Exception $e) {
            Log::error('e-Mola Payment Error', ['order' => $order->id, 'error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erro ao contactar e-Mola: ' . $e->getMessage(),
            ];
        }
    }

    public function checkStatus(string $transactionId): array
    {
        // iTcore gateway is synchronous (status returned at initiate time);
        // for async webhooks see handleCallback().
        return [
            'success' => true,
            'status'  => 'unknown',
            'message' => 'Status assíncrono via webhook.',
        ];
    }

    public function handleCallback(array $data): array
    {
        Log::info('e-Mola Callback', $data);

        $code = strtolower((string) ($data['output_ResponseCode'] ?? $data['status'] ?? ''));
        $txn  = $data['output_TransactionID'] ?? $data['transaction_id'] ?? null;

        if (in_array($code, ['successfully', 'completed', 'paid', 'success'], true)) {
            return ['success' => true, 'transaction_id' => $txn, 'status' => 'paid'];
        }

        return [
            'success'        => false,
            'transaction_id' => $txn,
            'status'         => 'failed',
            'error'          => $data['output_ResponseDesc'] ?? $data['message'] ?? 'Payment failed',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        // iTcore reembolsos requerem fluxo manual / portal Movitel — não suportado via API neste gateway.
        return [
            'success' => false,
            'message' => 'Reembolso e-Mola tem de ser processado manualmente no portal Movitel.',
        ];
    }
}
