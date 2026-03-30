<?php

namespace App\Enums\Payments;

enum StatutEnum: string
{
	case DRAFT = 'draft';
	case PENDING = 'pending';
	case IN_PROGRESS = 'in_progress';

	public function label(): string
	{
		return match ($this) {
			self::DRAFT => 'Draft',
			self::PENDING => 'Pending',
			self::IN_PROGRESS => 'In Progress',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}
