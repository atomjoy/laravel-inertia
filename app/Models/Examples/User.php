<?php

namespace App\Models;

use App\Traits\Models\HasSearch;
use App\Traits\Models\HasUserIsRole;
use App\Traits\Models\HasUserRelations;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, Notifiable;
	use HasApiTokens, HasRoles;
	use HasUserIsRole, HasUserRelations, HasSearch;

	/**
	 * Default guard name
	 *
	 * @var string
	 */
	protected $guard = 'web';

	/**
	 * Display relations with model
	 *
	 * @var array
	 */
	// protected $with = ['roles', 'permissions'];

	/**
	 * Display relations with model
	 *
	 * @var array
	 */
	// protected $appends = ['permission'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'last_name',
		'location',
		'image',
		'bio',
		'mobile_prefix',
		'mobile_number',
		'address_line_one',
		'address_line_two',
		'address_country',
		'address_city',
		'address_state',
		'address_postal',
		'prefer_sms',
		'two_factor',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'password' => 'hashed',
			'email_verified_at' => 'datetime:Y-m-d H:i:s',
			'created_at' => 'datetime:Y-m-d H:i:s',
			'updated_at' => 'datetime:Y-m-d H:i:s',
			'deleted_at' => 'datetime:Y-m-d H:i:s',
			'published_at' => 'datetime:Y-m-d H:i:s',
		];
	}

	/**
	 * Default guard name
	 *
	 * @var string
	 */
	public function getDefaultGuardName()
	{
		return $this->guard;
	}

	/**
	 * Get all permissions
	 *
	 * @var string
	 */
	protected function getPermissionAttribute()
	{
		return $this->getAllPermissions();
	}

	/**
	 * Get the user's largest order.
	 */
	public function largestOrder(): HasOne
	{
		return $this->hasOne(Order::class)->ofMany('gross_amount', 'max');
		// return $this->orders()->one()->ofMany('gross_amount', 'max');
	}
}
