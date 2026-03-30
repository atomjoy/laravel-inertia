<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Events\UploadAvatar;
use App\Exceptions\JsonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UploadAvatarRequest;
use App\Upload\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadAvatarController extends Controller
{
	function index(UploadAvatarRequest $request)
	{
		try {
			$user =  Auth::user();

			$path = (new UserImage())->upload($request, $user);

			UploadAvatar::dispatch($user, $path);

			return response()->json([
				'message' => __('upload.avatar.success'),
				'avatar' => $path,
			], 200);
		} catch (Throwable $e) {
			report($e->getMessage());
			throw new JsonException(__('upload.avatar.error'), 422);
		}
	}

	function remove(Request $request)
	{
		try {
			$user = Auth::user();
			$path = '/media/avatars/' . $user->id . '.webp';
			if (Storage::exists($path)) {
				Storage::delete($path);
				$user->image = '/default/avatar.webp';
				$user->save();
			}
			return response()->json([
				'message' => __('remove.avatar.success'),
				'user' => Auth::user()->fresh(['roles', 'permissions'])
			], 200);
		} catch (Throwable $e) {
			report($e);
			throw new JsonException(__('remove.avatar.error'), 422);
		}
	}

	/**
	 *	Get storage image content.
	 */
	public function show(Request $request)
	{
		try {
			$path = '/media/avatars/' . Auth::id() . '.webp';
			if (Storage::exists($path)) {
				return Storage::response($path);
			}
			return response()->file(public_path('/default/avatar.webp'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	/**
	 *	Get storage file url.
	 */
	public function getUrl(Request $request)
	{
		try {
			$path = trim(strip_tags(stripslashes(request('path'))));
			if (Storage::exists($path)) {
				return Storage::url($path);
			}
			return request()->getSchemeAndHttpHost() . '/default/avatar.webp';
		} catch (Throwable $e) {
			return request()->getSchemeAndHttpHost() . '/default/error.webp';
		}
	}
}
