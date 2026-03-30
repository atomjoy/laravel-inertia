<?php

namespace App\Enums\Payments;

enum PaymentMethodsEnum: string
{
	case TRANSFER = 'transfer';
	case MONEY = 'money';
	case CARD = 'card';
	case ONLINE = 'online';

	public function label(): string
	{
		return match ($this) {
			static::TRANSFER => 'Bank transfers',
			static::MONEY => 'Money payments',
			static::CARD => 'Card payments',
			static::ONLINE => 'Payment gateways',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}
