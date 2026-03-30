<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
	/** @use HasFactory<\Database\Factories\AttributeFactory> */
	// use HasFactory;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function skus(): BelongsToMany
	{
		return $this->belongsToMany(Sku::class, 'attribute_sku')->using(AttributeSku::class)->withPivot(['property_id']);
	}

	public function scopeSize($query)
	{
		$query->where('name', 'Size');
	}

	public function scopeColour($query)
	{
		$query->where('name', 'Color');
	}
}
