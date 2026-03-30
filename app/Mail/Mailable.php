<?php

namespace App\Mail;

use Illuminate\Mail\Mailable as BaseMailable;

abstract class Mailable extends BaseMailable
{
	/**
	 * Send the message using the given mailer.
	 *
	 * @param  \Illuminate\Contracts\Mail\Factory|\Illuminate\Contracts\Mail\Mailer  $mailer
	 * @return \Illuminate\Mail\SentMessage|null
	 */
	public function send($mailer)
	{
		// Add variable to mailable message (or put this in mail build() method)
		$this->withSymfonyMessage(function ($message) {
			$message->mailable = get_class($this);
		});

		parent::send($mailer);
	}
}

/*

// In event handler() i want to check what mailable class used and stop it.
if ($event->message->mailable == 'Ordershipped') {
    return false;
}

// Symfony\Component\Mime\Email email message class
Event::assertDispatched(MessageSent::class, function ($e) use ($email, $name) {
    $html = $e->message->getHtmlBody();
});
*/
