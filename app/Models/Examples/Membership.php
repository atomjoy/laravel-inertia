<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Membership extends Model
{
	/** @use HasFactory<\Database\Factories\MembershipFactory> */
	use HasFactory;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	/**
	 * Get all of the skus with the course.
	 */
	public function skus(): MorphToMany
	{
		return $this->morphToMany(Sku::class, 'virtuable')->chaperone();
	}
}
