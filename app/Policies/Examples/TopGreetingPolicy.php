<?php

namespace App\Policies;

use App\Models\TopGreeting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TopGreetingPolicy
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
	public function view(User $user, TopGreeting $topGreeting): bool
	{
		// Disabled here
		// if ($user->id == $topGreeting->user_id) {
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
	public function update(User $user, TopGreeting $topGreeting): bool
	{
		// Disabled here
		// if ($user->id == $topGreeting->user_id) {
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
	public function delete(User $user, TopGreeting $topGreeting): bool
	{
		// Disabled here
		// if ($user->id == $topGreeting->user_id) {
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
	public function restore(User $user, TopGreeting $topGreeting): bool
	{
		return $this->delete($user, $topGreeting);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, TopGreeting $topGreeting): bool
	{
		return $this->delete($user, $topGreeting);
	}
}
