<?php

namespace App\Upload;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserImage
{
	/**
	 * Update model image.
	 */
	public function upload($request, User $model)
	{
		$path = '/default/avatar.webp';

		if ($request->file('image')) {
			$path = '/media/avatars/' . $model->id . '.webp';
			$image = (new ImageManager(new Driver()))
				->read($request->file('image'))
				->resize(
					config('default.avatar_image_width', 256),
					config('default.avatar_image_height', 256)
				)->toWebp();

			Storage::put($path, (string) $image);
			$model->image = $path;
			$model->save();
		}

		return $path;
	}

	/**
	 * Delete model image.
	 */
	public function delete(User $model)
	{
		try {
			if (Storage::exists($model->image)) {
				Storage::delete($model->image);
				$model->image = null;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}
}
