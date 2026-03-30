<?php

namespace App\Enums\Payments;

enum OrderStatusEnum: string
{
	case PENDING = 'pending';
	case WAITING = 'waiting';
	case PROCESSING = 'processing';
	case COMPLETED = 'completed';
	case CANCELED = 'canceled';
	case REFUNDED = 'refunded';
	case FAILED = 'failed';
	case DRAFT = 'draft';

	public function label(): string
	{
		return match ($this) {
			self::PENDING => 'Pending for payment',
			self::WAITING => 'Waiting for confirmation',
			self::PROCESSING => 'In progress',
			self::COMPLETED => 'Fulfilled',
			self::CANCELED => 'canceled',
			self::REFUNDED => 'Refunded',
			self::FAILED => 'Failed',
			self::DRAFT => 'Draft',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public function description(): string
	{
		return match ($this) {
			self::PENDING => 'The order has been placed, but the payment is still pending or hasn not been initiated. This is common for methods like bank transfers.',
			self::WAITING => 'The payment has been made but requires manual confirmation by the store owner or payment provider.',
			self::PROCESSING => 'The payment has been successfully validated, and the order is now in the fulfillment stage, like shipping. They will remain in this state until you manually change their state to another one. All orders require processing except those in which all products are both Virtual and Downloadable.',
			self::COMPLETED => 'The payment was successful, and the order has been fulfilled and delivered.',
			self::CANCELED => 'The order has been canceled by the admin or the customer.',
			self::REFUNDED => 'The order has been canceled, and a refund has been issued to the customer.',
			self::FAILED => 'The payment was declined by the payment gateway or the customers bank.',
			self::DRAFT => 'For instance, if a customer places a custom order for a product that requires confirmation of size or color, you can save the order as a draft while waiting for their response.',
			default => throw new \Exception('Unknown enum value.'),
		};
	}

	public function color(): string
	{
		return match ($this) {
			self::PENDING => 'gray',
			self::WAITING => 'yellow',
			self::PROCESSING => 'blue',
			self::COMPLETED => 'green',
			self::CANCELED => 'red',
			self::REFUNDED => 'purple',
			self::FAILED => 'red',
			self::DRAFT => 'orange',
			default => 'gray',
		};
	}

	public static function toName($value): string
	{
		return self::from($value)->name;
	}

	public function finalized(): bool
	{
		return in_array($this, [self::COMPLETED, self::CANCELED, self::REFUNDED, self::FAILED]);
	}

	public static function options(): array
	{
		static $options = null;

		if ($options === null) {
			$options = collect(self::cases())->map(fn($case) => [
				'value' => $case->value,
				'label' => $case->label(),
				'color' => $case->color(),
				'description' => $case->description(),
			])->toArray();
		}

		return $options;
	}
}
