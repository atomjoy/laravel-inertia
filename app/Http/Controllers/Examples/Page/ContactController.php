<?php

namespace App\Http\Controllers\Page;

use Throwable;
use App\Http\Requests\Page\StoreContactRequest;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 */
	public function store(StoreContactRequest $request)
	{
		$valid = $request->validated();
		$valid['ip'] = $request->ip();

		try {
			if (!empty(env('CONTACT_EMAIL_ADDRESS'))) {
				Mail::to(env('CONTACT_EMAIL_ADDRESS'))->send(new ContactMail($valid['email'], $valid['name'], $valid['message'], $valid['ip']));
			}

			// $file = $request->file('file');
			// if ($file) {
			// 	$extension = $file->extension();
			// 	$path = 'id-' . $contact->id . '.' . $extension;
			// 	Storage::disk('local')->putFileAs('contact', $file, $path);
			// 	$contact->file = 'contact/' . $path;
			// 	$contact->save();
			// }

			return response()->json([
				'message' => __('Message has been sent.'),
			]);
		} catch (Throwable $e) {
			report($e);
			return response()->json([
				'error' => $e->getMessage(),
			], 422);
		}
	}
}
