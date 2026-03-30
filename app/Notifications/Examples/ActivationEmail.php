<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivationEmail extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(User $notifiable): MailMessage
	{
		$url = request()->getSchemeAndHttpHost() .
			'/activate/' . $notifiable->getKey() . '/' . $notifiable->remember_token;

		return (new MailMessage)
			->subject(__('email.register.subject'))
			->greeting(__('email.register.welcome'), $notifiable->name)
			->line(__('email.register.message'))
			->action(__('email.register.button'), url($url))
			->line(__('email.register.info'));
		// ->theme('custom')
		// ->view('email.auth.activation', ['url' => $url]);
		// ->theme('auth_' . config('mail.markdown.theme'))
		// ->markdown(email.auth.activation', ['url' => $url]);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
			//
		];
	}
}
