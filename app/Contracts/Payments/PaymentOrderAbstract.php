<?php

namespace App\Contracts\Payments;

abstract class PaymentOrderAbstract
{
    public $payment_type = 'bank'; // bank, online, money, card
    public $payment_gateway = 'none'; // none, payu, paypal, stripe
    public $payment_currency = 'pln'; // pln, usd, eur
    public $status = 'pending';
    public $cost;
    public $firstname;
    public $lastname;
    public $phone;
    public $email;
}
