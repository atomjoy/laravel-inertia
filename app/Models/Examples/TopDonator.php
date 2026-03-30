<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use App\Traits\Models\HasUserIsRole;
use App\Traits\Models\HasUserRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopDonator extends Model
{
	/** @use HasFactory<\Database\Factories\TopDonatorFactory> */
	use HasFactory;
	use HasUserIsRole, HasUserRelations, HasSearch;

	protected $with = [];

	protected $guarded = [];

	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
		];
	}
}
