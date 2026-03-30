<?php

namespace App\Policies;

use App\Models\Widget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class WidgetPolicy
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
	public function view(User $user, Widget $widget): bool
	{
		// Disabled here
		// if ($user->id == $widget->user_id) {
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
	public function update(User $user, Widget $widget): bool
	{
		// Disabled here
		// if ($user->id == $widget->user_id) {
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
	public function delete(User $user, Widget $widget): bool
	{
		// Disabled here
		// if ($user->id == $widget->user_id) {
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
	public function restore(User $user, Widget $widget): bool
	{
		return $this->delete($user, $widget);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Widget $widget): bool
	{
		return $this->delete($user, $widget);
	}
}
