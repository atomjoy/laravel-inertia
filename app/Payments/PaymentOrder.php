<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentOrderAbstract;
use App\Contracts\Payments\PaymentOrderInterface;

class PaymentOrder extends PaymentOrderAbstract implements PaymentOrderInterface
{
    // public function __construct(protected $order = null) {}

    function paymentOrderId(): string
    {
        return (string) 12321;
    }

    function paymentOrderCost(): int
    {
        return 2499;
    }

    function paymentOrderFirstname(): string
    {
        return 'Alex';
    }

    function paymentOrderLastname(): string
    {
        return 'Moore';
    }

    function paymentOrderPhone(): string
    {
        return '+48100200300';
    }

    function paymentOrderEmail(): string
    {
        return 'order@example.com';
    }

    function paymentOrderCurrency(): string
    {
        return 'PLN';
    }
}
