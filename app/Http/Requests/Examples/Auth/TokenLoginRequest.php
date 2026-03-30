<?php

namespace App\Http\Requests\Auth;

use App\Events\TokenLoginUser;
use App\Events\TokenLoginUserError;
use App\Exceptions\JsonException;
use App\Models\Auth\F2acode;
use App\Models\User;
use App\Notifications\TwoFactorEmail;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class TokenLoginRequest extends FormRequest
{
	// protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow all
	}

	public function rules()
	{
		$email = 'email:rfc,dns';
		if (env('APP_DEBUG') == true) {
			$email = 'email';
		}

		return [
			'email' => ['required', $email, 'max:191'],
			'password' => [
				'required',
				Password::min(11)->letters()->mixedCase()->numbers()->symbols(),
				'max:50'
			],
			'device_name' => 'required|string|min:5'
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'errors' => $validator->errors(),
			'error' =>  $validator->errors()->first(),
		], 422));
	}

	function prepareForValidation()
	{
		$this->merge([
			'device_name' => Str::slug($this->device_name)
		]);
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function authenticate(): User
	{
		$this->ensureIsNotRateLimited();

		$user = User::where('email', $this->email)->first();

		if (! $user instanceof User || ! Hash::check($this->password, $user->password)) {
			RateLimiter::hit(
				$this->throttleKey(),
				config('default.ratelimit_login_time', 300)
			);

			TokenLoginUserError::dispatch($this->email);

			throw new JsonException(__('login.failed'), 422);
		} else {
			// Limit tokens
			if (config('sanctum.tokens_limit', 10) > 0) {
				if ($user && $user->tokens->count() >= config('sanctum.tokens_limit', 10)) {
					$user->tokens()->delete();
				}
			}

			TokenLoginUser::dispatch($user);
		}

		if (empty($user->email_verified_at)) {
			throw new JsonException(__('login.unverified'), 422);
		}

		RateLimiter::clear($this->throttleKey());

		return $user;
	}

	/**
	 * 2FA Authentication
	 */
	public function createTwoFactor(User $user)
	{
		$code = random_int(123123, 999999);
		$hash = Str::uuid();

		if (app()->runningUnitTests()) {
			$hash = 'test-hash-f2a-123';
			$code = 888777;
		}

		try {
			$user->notifyNow(new TwoFactorEmail($code));

			F2acode::create([
				'user_id' => $user->id,
				'code' => $code,
				'hash' => $hash
			]);
		} catch (\Throwable $e) {
			throw new JsonException(__("login.f2a_email_error"), 422);
		}

		return $hash;
	}

	public function ensureIsNotRateLimited(): void
	{
		if (!RateLimiter::tooManyAttempts($this->throttleKey(), config('default.ratelimit_login_count', 20))) {
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
		return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
	}
}
