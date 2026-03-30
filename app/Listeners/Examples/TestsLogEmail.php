<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

/**
 * Log notifications email
 */
class TestsLogEmail
{
	public function handle(MessageSending $event)
	{
		$message = $event->message;

		if (app()->runningUnitTests()) {
			Log::build([
				'driver' => 'single',
				'path' => storage_path('logs/testing.log')
			])->info($message->toString());
		}

		// $event->message->getSubject();
		// $event->message->getBody();
		// $event->message->getHeaders();
	}
}
