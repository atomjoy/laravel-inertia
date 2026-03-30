<?php

namespace App\Http\Controllers\Storage;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
	function avatar(Request $request)
	{
		try {
			$path = $request->input('path');
			if ($path != null && Storage::exists($path)) {
				return Storage::response($path);
			}
			return response()->file(public_path('/default/avatar.webp'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	function show(Request $request)
	{
		try {
			$path = $request->input('path');
			if (Storage::exists($path)) {
				return Storage::response($path);
			}
			return response()->file(public_path('/default/default.webp'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	function gif(Request $request)
	{
		try {
			$path = $request->input('path');
			if (Storage::exists($path)) {
				return Storage::response($path);
			}
			return response()->file(public_path('/default/default.gif'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	function cache(Request $request)
	{
		try {
			$path = $request->input('path');
			if (Storage::exists($path)) {
				$img = Cache::store('file')->remember(md5($path), now()->addMinutes(15), function () use ($path) {
					return Storage::get($path);
				});
				return response($img)->header('Content-Type', 'image/webp');
			}
			return response()->file(public_path('/default/default.webp'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	function resize(Request $request)
	{
		try {
			$path = $request->input('path');
			if (Storage::exists($path)) {
				$image = (new ImageManager(new Driver()))->read(Storage::get($path));
				if (in_array(request('size'), ['s', 'm', 'l', 'xl'])) {
					if (request('size') == 's') {
						$image->scale(width: 360);
					}
					if (request('size') == 'm') {
						$image->scale(width: 480);
					}
					if (request('size') == 'l') {
						$image->scale(width: 768);
					}
					if (request('size') == 'xl') {
						$image->scale(width: 1024);
					}
				} else {
					$image->scale(width: config('default.resize_max_image_width', 1280));
				}
				$encoded = $image->toWebp();
				return response($encoded)->header('Content-Type', 'image/webp');
			}
			return response()->file(public_path('/default/default.webp'));
		} catch (Throwable $e) {
			return response()->file(public_path('/default/error.webp'));
		}
	}

	function url(Request $request)
	{
		try {
			$path = $request->input('path');

			if (Storage::exists($path)) {
				return Storage::url($path);
			}

			return '/default/avatar.webp';
		} catch (Throwable $e) {
			return '/default/error.webp';
		}
	}

	/**
	 * Download file
	 *
	 * @param Request $request
	 * @return response
	 */
	function file(Request $request)
	{
		try {
			$path = $request->input('path');

			if (Storage::exists($path)) {
				return Storage::download($path);
			}

			return response()->json([
				'error' => 'Invalid file name'
			], 422);
		} catch (Throwable $e) {
			return response()->json([
				'error' => 'File error'
			], 422);
		}
	}
}


/*
// Display image response, resize on the fly
Route::get('/img', function () {
	return '<img src="/img/show?path=avatars/1.webp>';
	return '<img src="/img/resize?path=avatars/1.webp&size=s">';
});

<picture>
  <source media="(min-width:1024px)" srcset="/img/resize?path=media/images/111.webp">
  <source media="(min-width:800px)" srcset="/img/resize?path=media/images/111.webp&size=xl">
  <source media="(min-width:600px)" srcset="/img/resize?path=media/images/111.webp&size=l">
  <source media="(min-width:400px)" srcset="/img/resize?path=media/images/111.webp&size=m">
  <img src="/img/resize?path=media/images/111.webp&size=s" alt="Flowers" style="width:auto;">
</picture>
*/
