<?php

namespace App\Enums\Auth;

/**
 * User panel roles
 */
enum MembershipEnum: string
{
    case LITE = 'lite';
    case PRO = 'pro';
    case VIP = 'vip';

    public function label(): string
    {
        return match ($this) {
            static::LITE => 'Lite users',
            static::PRO => 'Pro users',
            static::VIP => 'Vip users',
            default => throw new \Exception('Unknown enum value.'),
        };
    }
}
