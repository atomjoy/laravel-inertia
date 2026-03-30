<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Product variant
 */
class Sku extends Model
{
	/** @use HasFactory<\Database\Factories\SkuFactory> */
	// use HasFactory;

	protected $with = ['attributes', 'images', 'files', 'product'];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	/**
	 * Get the parent product.
	 */
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	/**
	 * Get the variant attributes with properties.
	 */
	public function attributes(): BelongsToMany
	{
		return $this->belongsToMany(Attribute::class, 'attribute_sku')->using(AttributeSku::class)->withPivot('property_id');
		// return $this->belongsToMany(Attribute::class, 'attribute_sku')->using(AttributeSku::class)->withPivot('property_id')->wherePivotIn('property_id', [1, 2, 3]);
		// return $this->belongsToMany(Attribute::class, 'attribute_sku')->using(AttributeSku::class)->as('pivot_name_here')->withPivot('property_id')->wherePivot('attribute_id', 1);
	}

	/**
	 * Get product images
	 */
	public function images(): MorphMany
	{
		return $this->morphMany(Image::class, 'imageable');
		// With parent
		// return $this->morphMany(Image::class, 'imageable')->chaperone();
	}

	public function latestImage(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')->latestOfMany();
	}

	public function oldestImage(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')->oldestOfMany();
	}

	public function bestImage(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')->ofMany('likes', 'max');
	}

	/**
	 * Get product files
	 */
	public function files(): MorphMany
	{
		return $this->morphMany(File::class, 'fileable');
		// With parent
		// return $this->morphMany(File::class, 'fileable')->chaperone();
	}

	public function latestFile(): MorphOne
	{
		return $this->morphOne(File::class, 'fileable')->latestOfMany();
	}

	public function oldestFile(): MorphOne
	{
		return $this->morphOne(File::class, 'fileable')->oldestOfMany();
	}

	public function bestFile(): MorphOne
	{
		return $this->morphOne(File::class, 'fileable')->ofMany('likes', 'max');
	}

	/**
	 * Get the parent model for courses, memberships, subscription.
	 */
	public function virtuable(): MorphTo
	{
		return $this->morphTo();
	}

	// /**
	//  * Get all of the courses that are assigned to this product.
	//  */
	// public function courses(): MorphToMany
	// {
	// 	return $this->morphedByMany(Course::class, 'virtuable');
	// }

	// /**
	//  * Get all of the memberships that are assigned to this product.
	//  */
	// public function memberships(): MorphToMany
	// {
	// 	return $this->morphedByMany(Membership::class, 'virtuable');
	// }
}
