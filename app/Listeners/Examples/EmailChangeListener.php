<?php

namespace App\Listeners;

use Throwable;
use App\Models\User;
use App\Events\EmailChange;
use App\Events\EmailChangeMail;
use App\Events\EmailChangeMailError;
use App\Exceptions\JsonException;
use App\Notifications\ChangeEmail;
use App\Notifications\ChangeRecoveryEmail;

class EmailChangeListener
{
	public function handle(EmailChange $event)
	{
		$this->sendEmail($event->user, $event->code);
	}

	public function sendEmail(User $user, $code)
	{
		if (config('send_email_change_email', true)) {
			try {
				$user->notifyNow(new ChangeEmail($code));
				$user->notifyNow(new ChangeRecoveryEmail($code));

				EmailChangeMail::dispatch($user);
			} catch (Throwable $e) {
				report($e);
				EmailChangeMailError::dispatch($user);
				throw new JsonException(__("email.change.email.error"), 422);
			}
		}
	}
}
