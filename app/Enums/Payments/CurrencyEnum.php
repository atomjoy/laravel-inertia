<?php

namespace App\Enums\Payments;

enum CurrencyEnum: string
{
	case PLN = 'pln';
	case EUR = 'eur';
	case USD = 'usd';

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}
