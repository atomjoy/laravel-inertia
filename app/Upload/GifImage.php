<?php

namespace App\Upload;

use App\Models\Gif;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GifImage
{
	/**
	 * Update model image.
	 */
	public function upload($request, Gif $model)
	{
		try {
			if ($request->file('image')) {
				$path = '/media/gifs/' . $model->id . '.gif';

				$image = (new ImageManager(new Driver()))
					->read($request->file('image'))
					->toGif();

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
	public function delete(Gif $model)
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
