<?php

namespace App\Http\Requests;

use App\Enums\Payments\CurrencyEnum;
use App\Enums\Payments\PaymentGatewaysEnum;
use App\Enums\Payments\PaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class UpdateDonationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		$email = 'email:rfc,dns';

		if (env('APP_DEBUG') == true) {
			$email = 'email';
		}

		return [
			'name' => ['required', 'min:2', 'max:10'],
			'email' => ['required', $email, 'max:161'],
			'amount' => ['required', Rule::numeric()->min(3.00)->decimal(2)],
			'message' => ['required', 'min:2', 'max:255'],
			'last_name' => ['sometimes', 'nullable', 'min:2', 'max:20'],
			'phone' => ['sometimes', 'nullable', 'min:11', 'regex:/^[\+]{1}([0-9]+){10,}$/'],
			'gif' => ['sometimes', 'nullable', Rule::numeric()->min(1)],
			// Options
			'gateway' => ['sometimes', 'nullable', Rule::enum(PaymentGatewaysEnum::class)],
			'status' => ['sometimes', 'nullable', Rule::enum(PaymentStatusEnum::class)],
			// 'currency' => ['sometimes', 'nullable', Rule::enum(CurrencyEnum::class)],
			// 'payment_id' => 'sometimes|nullable',
			// 'external_id' => 'sometimes|nullable',
			// 'url' => 'sometimes|nullable',
			'ip' => 'sometimes|nullable',
			'is_seen' => 'sometimes|nullable',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'errors' => $validator->errors(),
			'error' =>  $validator->errors()->first(),
			// 'error' =>  __('donate.form_error'),
		], 422));
	}

	function prepareForValidation()
	{
		$this->merge([
			'amount' => number_format($this->amount, 2, '.', ''), // Change decimal to int
			'currency' => strtolower($this->currency),
		]);
	}
}
