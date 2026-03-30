<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EmailChangeRecoverRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow logged
	}

	public function rules()
	{
		return [
			'id' => 'required|numeric|min:1',
			'code' => 'required|string|min:6|max:500',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'errors' => $validator->errors(),
			'error' => $validator->errors()->first()
		], 422));
	}

	function prepareForValidation()
	{
		$this->merge([
			'id' => (string) request()->route('id'),
			'code' => (string) trim(strip_tags(request()->route('code')))
		]);
	}
}
