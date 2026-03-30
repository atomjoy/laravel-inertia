<?php

namespace App\Payments\Gateways;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Payments\PaymentOrderInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'PayPal';
    }

    public function process(PaymentOrderInterface $order): string
    {
        // Proccess payment here

        return 'https://paypal.example.com/pay';
    }

    public function refund(string $id): string
    {
        // Proccess refund here

        return $id;
    }
}
