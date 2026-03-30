<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UploadAvatarRequest extends FormRequest
{
	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return Auth::check(); // Allow logged
	}

	public function rules()
	{
		return [
			'image' => [
				'required',
				'mimes:webp,jpeg,jpg,png,gif',
				Rule::dimensions()
					->minWidth(config('default.avatar_min_pixels', 64))
					->minHeight(config('default.avatar_min_pixels', 64)),
				Rule::dimensions()
					->maxWidth(config('default.avatar_max_pixels', 1025))
					->maxHeight(config('default.avatar_max_pixels', 1025)),
				Rule::file()->types(['webp', 'jpeg', 'jpg', 'png', 'gif'])
					->max(config('default.max_upload_size_mb', 5) * 1024),
			]
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
		// $this->merge(
		//     collect(request()->json()->all())->only(['avatar'])->toArray()
		// );
	}
}
