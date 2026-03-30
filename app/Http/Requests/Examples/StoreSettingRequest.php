<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreSettingRequest extends FormRequest
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
			'name' => 'required|min:3|alpha_dash|unique:settings',
			'value' => 'sometimes|nullable',
			'input' => 'required|nullable|in:text,textarea,checkbox,color,image,file_zip,file_pdf,file_mp3,datetime',
			'image' => 'sometimes|nullable|image|mimes:webp,png,jpg,jpeg',
			'file_mp3' => 'sometimes|nullable|mimes:mp3',
			'file_zip' => 'sometimes|nullable|mimes:zip',
			'file_pdf' => 'sometimes|nullable|mimes:pdf',
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
			'name' => Str::slug($this->name, "_"),
		]);
	}
}
