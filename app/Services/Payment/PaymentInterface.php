<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;

interface PaymentInterface
{
    /**
     * Initiate a payment request.
     * returns array with 'url' to redirect or 'data' for form.
     */
    public function initiate(float $amount, string $transactionId): array;

    /**
     * Verify the payment callback/response.
     */
    public function verify(Request $request): array; // returns ['status' => true/false, 'amount' => 100, 'refId' => '...']
    
    /**
     * Get the gateway name.
     */
    public function getName(): string;
}
