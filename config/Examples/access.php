<?php

return [
	// Admin email for AppAdminSeeder
	'admin_email' => 'admin@example.com',

	// Force admin panel 2FA
	'force_admin_2fa' => false,

	// Disable admin firewall
	'admin_allowed_ips' => env('ADMIN_ALLOWED_IPS', null),

	// Enable admin firewall
	// 'admin_allowed_ips' => env('ADMIN_ALLOWED_IPS', '127.0.0.1|10.0.0.0/8'),

	// Youtube settings (don't change)
	'youtube' => [
		// Live viewers videoId file
		'current' => 'youtube-current.txt',
		// Live viewers refresh cache in seconds
		'current_refresh' => 60,
	]
];
