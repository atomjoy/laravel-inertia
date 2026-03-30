<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
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
	public function view(User $user, Setting $model): bool
	{
		// Disabled here
		// if ($user->id == $model->user_id) {
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
	public function update(User $user, Setting $model): bool
	{
		// Disabled here
		// if ($user->id == $model->user_id) {
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
	public function delete(User $user, Setting $model): bool
	{
		if ($user->isAdmin() || $user->isWorker()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Setting $model): bool
	{
		return $this->delete($user, $model);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Setting $model): bool
	{
		return $this->delete($user, $model);
	}
}
