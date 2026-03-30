<?php

namespace App\Payments\Gateways;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Contracts\Payments\PaymentOrderInterface;

class PayuGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'Payu';
    }

    public function process(PaymentOrderInterface $order): string
    {
        // Proccess payment here

        return 'https://payu.example.com/pay';
    }

    public function refund(string $id): string
    {
        // Proccess refund here

        return $id;
    }
}
