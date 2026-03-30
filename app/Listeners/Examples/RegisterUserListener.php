<?php

namespace App\Listeners;

use Throwable;
use App\Models\User;
use App\Events\RegisterUser;
use App\Events\RegisterUserMail;
use App\Events\RegisterUserMailError;
use App\Exceptions\JsonException;
use App\Notifications\ActivationEmail;

class RegisterUserListener
{
	public function handle(RegisterUser $event)
	{
		$this->sendEmail($event->user);
	}

	public function sendEmail(User $user)
	{
		if (config('send_register_email', true)) {
			try {
				$user->notifyNow(new ActivationEmail());
				RegisterUserMail::dispatch($user);
			} catch (Throwable $e) {
				report($e);
				RegisterUserMailError::dispatch($user);
				throw new JsonException(__("register.email.error"), 422);
			}
		}
	}
}
