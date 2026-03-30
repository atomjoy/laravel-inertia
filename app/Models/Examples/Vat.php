<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vat extends Model
{
	/** @use HasFactory<\Database\Factories\VatFactory> */
	use HasFactory;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
		];
	}
}
