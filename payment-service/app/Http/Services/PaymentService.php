<?php

namespace App\Http\Services;

use App\Models\Payment;

class PaymentService
{

    public function processPayment($paymentData)
    {
        $paymentType = $paymentData['type'] ?? 'credit_card';

        $transactionId = $paymentData['transaction_id'] ?? uniqid('mock_', true);

        $status = rand(0, 1) ? 'paid' : 'payment_failed';

        $provider = match ($paymentData['payment_method']) {
            'credit_card' => 'picpay',
            'pix' => 'mercado_pago',
            'boleto' => 'pagseguro',
            default => 'unknown',
        };

        $payment = Payment::create([
            'order_id' => $paymentData['order_id'],
            'transaction_id' => $transactionId,
            'provider' => $provider,
            'payment_method' => $paymentType,
            'status' => $status,
            'details' =>  [
                'simulation' => true,
                'received' => $paymentData
            ],
        ]);

        if(!$payment) {
           return false;
        }

        return $payment;
    }


}