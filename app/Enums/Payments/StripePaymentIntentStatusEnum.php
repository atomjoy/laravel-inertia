<?php

namespace App\Enums\Payments;

enum StripePaymentIntentStatusEnum: string
{
	case REQUIRES_PAYMENT_METHOD = 'requires_payment_method';
	case REQUIRES_CONFIRMATION = 'requires_confirmation';
	case REQUIRES_ACTION = 'requires_action';
	case REQUIRES_CAPTURE = 'requires_capture';
	case PROCESSING = 'processing';
	case CANCELED = 'canceled';
	case FAILED = 'failed';
	case SUCCEEDED = 'succeeded';

	public static function toName($value): string
	{
		return self::from($value)->name;
	}
}

// Payment status:
// requires_payment_method (cards, blik)
// requires_confirmation (cards, blik)
// requires_action (cards, blik)
// requires_capture (waiting_for_confirmation)
// processing
// canceled
// failed
// succeeded
