<?php

namespace App\Payments\Actions;

class PayuToken
{
	function __construct(
		public $expires_in = 0,
		public $access_token = null,
		public $grant_type = 'client_credentials',
		public $token_type = 'bearer',
		public $refresh_token = null,
	) {}
}
