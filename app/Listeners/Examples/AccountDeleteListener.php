<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\AccountDelete;

class AccountDeleteListener
{
	public function handle(AccountDelete $event)
	{
		$this->deleteAccount($event->user);
	}

	public function deleteAccount(User $user)
	{
		$user->forceFill([
			'email' => 'del#' . uniqid() . '#' . str_replace('@', '@@', $user->email),
			'password' => 'del#' . md5(microtime()),
			'remember_token' => null
		])->save();
	}

	public function deleteProfil(User $user)
	{
		// $profile = \App\Models\Profile::where('user_id', $user->id)->first();
		// if ($profile instanceof Profile) {
		//     $profile->forceFill([
		//         'username' => '#del#' . time() . '#' . $profile->username,
		//         'deleted_at' => now()
		//     ])->save();
		// }
	}
}
