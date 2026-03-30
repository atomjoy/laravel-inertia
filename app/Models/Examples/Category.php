<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
	/** @use HasFactory<\Database\Factories\CategoryFactory> */
	use HasFactory;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	/**
	 * Get all products in this category
	 * @return BelongsToMany
	 */
	public function products(): BelongsToMany
	{
		return $this->belongsToMany(Product::class);
	}

	/**
	 * This will give model Parent
	 * @return BelongsTo
	 */
	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	/**
	 * This will give model Children
	 * @return HasMany
	 */
	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	/**
	 * Skus list
	 *
	 * @return array|collection
	 */
	public function skus()
	{
		return $this->hasManyThrough(
			Sku::class,
			CategoryProduct::class,
			'category_id',
			'product_id',
			'id',
			'product_id'
		);
	}

	/**
	 * This will give model Childrens until last node.
	 * @return HasMany
	 */
	public function childrenRecursive(): HasMany
	{
		return $this->children()->with('childrenRecursive');
	}

	/**
	 * This will give model Parents until root.
	 * @return BelongsTo
	 */
	public function parentRecursive(): BelongsTo
	{
		return $this->parent()->with('parentRecursive');
	}
}
