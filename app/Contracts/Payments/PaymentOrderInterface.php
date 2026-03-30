<?php

namespace App\Contracts\Payments;

interface PaymentOrderInterface
{
    function paymentOrderId(): string;
    function paymentOrderCost(): int;
    function paymentOrderFirstname(): string;
    function paymentOrderLastname(): string;
    function paymentOrderPhone(): string;
    function paymentOrderEmail(): string;
    function paymentOrderCurrency(): string;
}
