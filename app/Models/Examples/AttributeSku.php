<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeSku extends Pivot
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = true;

	public function property(): BelongsTo
	{
		return $this->belongsTo(Property::class, 'property_id');
	}

	public function attribute(): BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attribute_id');
	}
}
