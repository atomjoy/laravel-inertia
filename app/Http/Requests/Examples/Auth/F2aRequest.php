<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\JsonException;
use App\Models\Auth\F2acode;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class F2aRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow all
	}

	public function rules()
	{
		return [
			'hash' => 'required|min:6|max:64',
			'code' => 'required|min:3|max:32',
			'remember_me' => 'sometimes|boolean'
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'errors' => $validator->errors(),
			'error' => $validator->errors()->first(),
		], 422));
	}

	function prepareForValidation()
	{
		// $this->merge(
		// 	collect(request()->json()->all())->only(['hash', 'code', 'remember_me'])->toArray()
		// );
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function authenticate(): void
	{
		$this->ensureIsNotRateLimited();

		if (!Auth::check()) {
			RateLimiter::hit(
				$this->throttleKey(),
				config('default.ratelimit_login_time', 300)
			);

			$f2a = F2acode::where($this->only('hash', 'code'))->first();

			if ($f2a instanceof F2acode) {
				// Is code expired
				if ($f2a->created_at && $f2a->created_at->addMinutes(5)->isPast()) {
					throw new JsonException(__('login.f2a_expired'), 422);
				}

				Auth::login($f2a->user, $this->boolean('remember_me'));

				if (Auth::check()) {
					$f2a->delete();
				}
			} else {
				throw new JsonException(__('login.f2a_error'), 422);
			}
		}

		RateLimiter::clear($this->throttleKey());
	}

	public function ensureIsNotRateLimited(): void
	{
		if (!RateLimiter::tooManyAttempts($this->throttleKey(), config('default.ratelimit_login_count', 5))) {
			return;
		}

		event(new Lockout($this));

		$seconds = RateLimiter::availableIn($this->throttleKey());

		if (app()->runningUnitTests()) {
			$seconds = 60;
		}

		throw new JsonException(__('login.throttle', [
			'seconds' => $seconds,
			'minutes' => ceil($seconds / 60),
		]), 422);
	}

	public function throttleKey(): string
	{
		return Str::transliterate(Str::lower($this->input('hash')));
	}
}
