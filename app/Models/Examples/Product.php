<?php

namespace App\Models;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
	/** @use HasFactory<\Database\Factories\ProductFactory> */
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

	public function skus(): HasMany
	{
		return $this->hasMany(Sku::class, 'product_id');
	}

	public function attributes(): HasMany
	{
		return $this->hasMany(Attribute::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class)->with('parentRecursive');
		// return $this->belongsToMany(Category::class);
	}
}
