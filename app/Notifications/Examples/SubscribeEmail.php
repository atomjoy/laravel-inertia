<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscribeEmail extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(public $id)
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
	public function toMail(Subscriber $notifiable): MailMessage
	{
		$url = request()->getSchemeAndHttpHost() .
			'/api/subscribe/confirm/' . $this->id;

		return (new MailMessage)
			->subject(__('email.subscribe.subject'))
			->greeting(__('email.subscribe.welcome'), $notifiable->name)
			->line(__('email.subscribe.message'))
			->action(__('email.subscribe.button'), url($url))
			->line(__('email.subscribe.info'));
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
