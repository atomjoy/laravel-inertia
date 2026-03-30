<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreContactRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

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
			'name' => 'required|max:200',
			'email' => ['required', $email, 'max:191'],
			'message' => 'required|max:5000',
			'file' => [
				'sometimes',
				Rule::file()->types(['pdf', 'doc', 'docx'])->max(5 * 1024),
			]
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
			// 'name' => Str::slug($this->name, "_"),
		]);
	}
}
