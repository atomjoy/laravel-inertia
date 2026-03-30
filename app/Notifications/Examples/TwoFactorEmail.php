<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorEmail extends Notification
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
		$html = '<div style="float: left; width: 90%; margin-block: 10%; font-size: 20px; font-weight: bold; background: #f7f7f7; padding: 15px 20px; border: 1px solid #999; color: #999;">' . $this->code . '</div>';

		return (new MailMessage)
			->subject(__('email.f2a.subject'))
			->greeting(__('email.f2a.welcome'), $notifiable->name)
			->line(__('email.f2a.message'))
			->line($this->code)
			->line(__('email.f2a.expire'));
		// ->line(new HtmlString($html))
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
