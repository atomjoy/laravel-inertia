<?php

namespace App\Payments\Gateways;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Payments\PaymentOrderInterface;

class StripeGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'Stripe';
    }

    public function process(PaymentOrderInterface $order): string
    {
        // Proccess payment here

        return 'https://stripe.example.com/pay';
    }

    public function refund(string $id): string
    {
        // Proccess refund here

        return $id;
    }
}
