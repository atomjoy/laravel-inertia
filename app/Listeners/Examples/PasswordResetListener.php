<?php

namespace App\Listeners;

use Throwable;
use App\Models\User;
use App\Events\PasswordReset;
use App\Events\PasswordResetMail;
use App\Events\PasswordResetMailError;
use App\Exceptions\JsonException;
use App\Notifications\PasswordEmail;

class PasswordResetListener
{
	public function handle(PasswordReset $event)
	{
		$this->sendEmail($event->user, $event->password);
	}

	public function sendEmail(User $user, $pass)
	{
		if (config('send_password_email', true)) {
			try {
				$user->notifyNow(new PasswordEmail($pass));
				PasswordResetMail::dispatch($user);
			} catch (Throwable $e) {
				report($e);
				PasswordResetMailError::dispatch($user);
				throw new JsonException(__("reset.password.email.error"), 422);
			}
		}
	}
}
