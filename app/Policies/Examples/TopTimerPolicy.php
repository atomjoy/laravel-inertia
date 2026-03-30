<?php

namespace App\Policies;

use App\Models\TopTimer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TopTimerPolicy
{
	/**
	 * Perform pre-authorization checks on the model.
	 */
	public function before(User $user, string $ability): ?bool
	{
		return $user->isSuperAdmin() ? true : null; // Null is requred
	}

	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, TopTimer $topTimer): bool
	{
		// Disabled here
		// if ($user->id == $topTimer->user_id) {
		// 	return true;
		// }

		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, TopTimer $topTimer): bool
	{
		// Disabled here
		// if ($user->id == $topTimer->user_id) {
		// 	return true;
		// }

		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, TopTimer $topTimer): bool
	{
		// Disabled here
		// if ($user->id == $topTimer->user_id) {
		// 	return true;
		// }

		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, TopTimer $topTimer): bool
	{
		return $this->delete($user, $topTimer);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, TopTimer $topTimer): bool
	{
		return $this->delete($user, $topTimer);
	}
}
