<?php

namespace App\Enums\Payments;

enum ShippingMethodsEnum: string
{
	case DELIVERY = 'delivery';
	case PACKAGE = 'package';
	case INPOST = 'inpost';
	case DPD = 'dpd';

	public function label(): string
	{
		return match ($this) {
			self::DELIVERY => 'Virtual products, food, courses delivery',
			self::PACKAGE => 'Post delivery',
			self::INPOST => 'Inpost courier',
			self::DPD => 'Dpd courier',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}
