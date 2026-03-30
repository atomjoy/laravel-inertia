<?php

namespace App\Enums\Payments;

enum PaymentStatusEnum: string
{
	case NEW = 'new';
	case PENDING = 'pending';
	case WAITING = 'waiting';
	case COMPLETED = 'completed';
	case CANCELED = 'canceled';
	case REFUNDED = 'refunded';
	case REJECTED = 'rejected';
	case FAILED = 'failed';

	public static function toName($value): string
	{
		return self::from($value)->name;
	}

	// public static function getByName($name)
	// {
	// 	return match ($name) {
	// 		'completed' => self::COMPLETED,
	// 	};
	// }
}
