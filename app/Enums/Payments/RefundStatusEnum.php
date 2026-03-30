<?php

namespace App\Enums\Payments;

enum RefundStatusEnum: string
{
    case PENDING = 'pending';
    case CANCELED = 'canceled';
    case FINALIZED = 'finalized';

    public static function toName($value): string
    {
        return self::from($value)->name;
    }
}
