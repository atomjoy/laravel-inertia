<?php

namespace App\Upload;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostImage
{
	/**
	 * Update model image.
	 */
	public function upload($request, Post $model)
	{
		try {
			if ($request->file('image')) {
				$path = '/media/posts/' . $model->id . '.webp';

				$image = (new ImageManager(new Driver()))
					->read($request->file('image'))
					->resizeDown(
						config('default.post_image_width', 768)
					)->toWebp();

				Storage::put($path, (string) $image);

				$model->image = $path;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}

	/**
	 * Delete model image.
	 */
	public function delete(Post $model)
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
