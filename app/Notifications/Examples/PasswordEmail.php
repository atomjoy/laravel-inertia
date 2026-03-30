<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordEmail extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(protected $password)
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
		return (new MailMessage)
			->subject(__('email.password.subject'))
			->greeting(__('email.password.welcome'), $notifiable->name)
			->line(__('email.password.message'))
			->line($this->password);
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
