<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;

class ContactMail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 */
	public function __construct(public $email, public $name, public $content, public $ip = null) {}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: __('contact.form.subject'),
			replyTo: [
				new Address($this->email, $this->name),
			],
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'mail.contact',
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array<int, \Illuminate\Mail\Mailables\Attachment>
	 */
	public function attachments(): array
	{
		return [
			// Attachment::fromPath('/path/to/file')->as('contact.pdf')->withMime('application/pdf'),
		];
	}
}
