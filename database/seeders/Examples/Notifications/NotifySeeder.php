<?php

namespace Database\Seeders\Notifications;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Notifications\Messages\UserMessage;
use App\Notifications\UserNotification;

class NotifySeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// Create
		$msg = new UserMessage();
		$msg->setSenderId(1);
		$msg->setSenderName('Alex Crop');
		$msg->setSenderImage('/avatars/1.webp');
		$msg->setTitle('Alex ask for permissions?');
		$msg->setContent('Allow access to space.webp file.');
		$msg->setLink('/access/hash-123', 'Allow', 'notification-allow');
		$msg->setLink('/deny/hash-123', 'Deny', 'notification-deny');

		$msg1 = new UserMessage();
		$msg1->setSenderId(1);
		$msg1->setTitle('Permissions');
		$msg1->setContent('Witaj! Twoja aplikacja została zaakceptowana. Zapraszamy na stronę sklepu.');
		$msg1->setLink('https://example.com/promos', 'Promotions');

		$msg2 = new UserMessage();
		$msg2->setSenderId(1);
		$msg2->setSenderName('Super Admin');
		$msg2->setSenderImage('/avatars/admin/1.webp');
		$msg2->setTitle('Wiadomość tylko dla administratora!');
		$msg2->setContent('Witaj! Otrzymałeś nową prywatną wiadomość email.');

		$msg3 = new UserMessage();
		$msg3->setSenderId(1);
		$msg3->setSenderName('Admin Bot');
		$msg3->setTitle('Promocja dla administratora');
		$msg3->setContent('Witaj! Otrzymałeś nową prywatną wiadomość.');
		$msg3->setLink('https://example.com/promos', 'Promotions');

		// Send
		// $user = User::with('roles')->role('writer')->where('email', 'writer@github.com')->first();
		// $user = User::role('writer')->where('email', 'writer@github.com')->first();
		$user = User::role('writer')->first();
		$user->notify(new UserNotification($msg));
		$user->notifyNow(new UserNotification($msg1));
	}
}
