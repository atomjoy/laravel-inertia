<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreTopTimerRequest extends FormRequest
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
		return [
			'endtime' => 'sometimes|date:Y-m-d H:i:s',
			'text_size' => ['sometimes', 'nullable', Rule::numeric()->min(10)->max(100)],
			'text_weight' => ['sometimes', 'nullable', Rule::numeric()->min(100)->max(900)],
			'text_color' => 'sometimes|nullable|min:6|max:6',
			'text_font' => 'sometimes|nullable',
			'animation' => 'sometimes|nullable|in:animate__none,animate__bounce,animate__flash,animate__pulse,animate__wobble,animate__shakeX,animate__shakeY,animate__tada,animate__jello,animate__heartBeat',
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
			//
		]);
	}
}
