<?php

namespace App\Http\Requests;

use App\Enums\Auth\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserUpdateRequest extends FormRequest
{
	/**
	 * Show only first error message.
	 *
	 * @var boolean
	 */
	protected $stopOnFirstFailure = true;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check(); // Allow logged
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'min:2', 'max:50'],
			'last_name' => 'sometimes|min:3|max:50|nullable',
			'location' => 'sometimes|max:50|nullable',
			'bio' => 'sometimes|max:255|nullable',
			'mobile_prefix' => 'sometimes|numeric|nullable',
			'mobile_number' => 'sometimes|numeric|nullable',
			'address_line_one' => 'sometimes|max:50|nullable',
			'address_line_two' => 'sometimes|max:50|nullable',
			'address_city' => 'sometimes|max:50|nullable',
			'address_country' => 'sometimes|max:50|nullable',
			'address_state' => 'sometimes|max:50|nullable',
			'address_postal' => 'sometimes|max:50|nullable',
			'prefer_sms' => 'sometimes|numeric',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'errors' => $validator->errors(),
			'error' =>  $validator->errors()->first(),
		], 422));
	}

	/**
	 * Prepare inputs for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$this->merge([
			'prefer_sms' => (int) ($this->prefer_sms == 1),
		]);
	}
}
