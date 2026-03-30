<?php

namespace App\Enums\Payments;

enum PaymentGatewaysEnum: string
{
	case TRANSFER = 'transfer';
	case PAYU = 'payu';
	case PAYPAL = 'paypal';
	case STRIPE = 'stripe';

	public function label(): string
	{
		return match ($this) {
			static::TRANSFER => 'Transfer',
			static::PAYU => 'Payu',
			static::PAYPAL => 'PayPal',
			static::STRIPE => 'Stripe',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}
