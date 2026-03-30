<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class F2acode extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'f2acodes';

	protected $fillable = [
		'user_id',
		'code',
		'hash'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
		];
	}
}
