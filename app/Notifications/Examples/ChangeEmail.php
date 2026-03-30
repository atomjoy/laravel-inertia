<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeEmail extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(protected $code)
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
			'/change/email/' . $notifiable->getKey() . '/' . $this->code . '?locale=' . app()->getLocale();

		return (new MailMessage)
			->subject(__('email.change.subject'))
			->greeting(__('email.change.welcome'), $notifiable->name)
			->line(__('email.change.message'))
			->action(__('email.change.button'), url($url));
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
