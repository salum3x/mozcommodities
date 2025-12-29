<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment request
     */
    public function initiatePayment(Order $order, array $data): array;

    /**
     * Check payment status
     */
    public function checkStatus(string $transactionId): array;

    /**
     * Process webhook callback
     */
    public function handleCallback(array $data): array;

    /**
     * Refund a payment
     */
    public function refund(string $transactionId, float $amount): array;

    /**
     * Get gateway name
     */
    public function getName(): string;
}
