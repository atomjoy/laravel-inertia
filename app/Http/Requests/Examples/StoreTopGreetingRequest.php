<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreTopGreetingRequest extends FormRequest
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
			'style' => ['sometimes', 'nullable', Rule::numeric()->min(1)->max(50)],
			'audio' => 'sometimes|nullable',

			'bg' => 'sometimes|nullable|min:6|max:8',
			'border' => 'sometimes|nullable|min:6|max:8',
			'radius' => ['sometimes', 'nullable', Rule::numeric()->min(0)->max(50)],

			'font1' => 'sometimes|nullable',
			'color1' => 'sometimes|nullable|min:6|max:6',
			'size1' => ['sometimes', 'nullable', Rule::numeric()->min(10)->max(50)],
			'weight1' => ['sometimes', 'nullable', Rule::numeric()->min(100)->max(900)],
			'animation1' => 'sometimes|nullable|in:animate__none,animate__bounce,animate__flash,animate__pulse,animate__wobble,animate__shakeX,animate__shakeY,animate__tada,animate__jello,animate__heartBeat',

			'font2' => 'sometimes|nullable',
			'color2' => 'sometimes|nullable|min:6|max:6',
			'size2' => ['sometimes', 'nullable', Rule::numeric()->min(10)->max(50)],
			'weight2' => ['sometimes', 'nullable', Rule::numeric()->min(100)->max(900)],
			'animation2' => 'sometimes|nullable|in:animate__none,animate__bounce,animate__flash,animate__pulse,animate__wobble,animate__shakeX,animate__shakeY,animate__tada,animate__jello,animate__heartBeat',

			'font3' => 'sometimes|nullable',
			'color3' => 'sometimes|nullable|min:6|max:6',
			'size3' => ['sometimes', 'nullable', Rule::numeric()->min(10)->max(50)],
			'weight3' => ['sometimes', 'nullable', Rule::numeric()->min(100)->max(900)],
			'animation3' => 'sometimes|nullable|in:animate__none,animate__bounce,animate__flash,animate__pulse,animate__wobble,animate__shakeX,animate__shakeY,animate__tada,animate__jello,animate__heartBeat',
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
