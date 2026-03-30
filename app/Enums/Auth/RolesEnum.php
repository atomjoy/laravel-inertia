<?php

namespace App\Enums\Auth;

/**
 * Admin panel roles
 */
enum RolesEnum: string
{
	case SUPERADMIN = 'super_admin';
	case ADMIN = 'admin';
	case WORKER = 'worker';
	case PARTNER = 'partner';
	case EDITOR = 'editor';
	case WRITER = 'writer';

	public function label(): string
	{
		return match ($this) {
			static::SUPERADMIN => 'SuperAdmin users',
			static::ADMIN => 'Admin users',
			static::WORKER => 'Worker users',
			static::PARTNER => 'Partner users',
			static::EDITOR => 'Editor users',
			static::WRITER => 'Writer users',
			default => throw new \Exception('Unknown enum value.'),
		};
	}
}
