<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Property extends Model
{
	/** @use HasFactory<\Database\Factories\PropertyFactory> */
	use HasFactory;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	public function attribute(): BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attribute_id');
	}

	public function skus(): BelongsToMany
	{
		return $this->belongsToMany(Sku::class, 'attribute_sku')->using(AttributeSku::class)->withPivot(['property_id']);
	}
}
