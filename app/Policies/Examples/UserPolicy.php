<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
	/**
	 * Perform pre-authorization checks on the model.
	 */
	public function before(User $user, string $ability): ?bool
	{
		return $user->isSuperAdmin() ? true : null; // Null is requred
	}

	/**
	 * Determine whether the admin can view any models.
	 */
	public function viewAny(User $user): bool
	{
		if ($user->isAdmin()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the admin can view the model.
	 */
	public function view(User $user, User $model): bool
	{
		if ($user->id == $model->id) {
			return true;
		}

		if ($user->isAdmin()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the admin can create models.
	 */
	public function create(User $user): bool
	{
		if ($user->isAdmin()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the admin can update the model.
	 */
	public function update(User $user, User $model): bool
	{
		if ($user->id == $model->id) {
			return true;
		}

		if ($user->isAdmin()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the admin can delete the model.
	 */
	public function delete(User $user, User $model): bool
	{
		if ($user->isAdmin()) {
			return true;
		}

		return false;
	}

	/**
	 * Determine whether the admin can restore the model.
	 */
	public function restore(User $user, User $model): bool
	{
		return $this->delete($user, $model);
	}

	/**
	 * Determine whether the admin can permanently delete the model.
	 */
	public function forceDelete(User $user, User $model): bool
	{
		return $this->delete($user, $model);
	}
}
