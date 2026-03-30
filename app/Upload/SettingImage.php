<?php

namespace App\Upload;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class SettingImage
{
	/**
	 * Update model image.
	 */
	public function upload($request, Setting $model)
	{
		try {
			if ($request->file('image')) {
				$path = '/media/settings/' . $this->fileName($model) . '.webp';

				$image = (new ImageManager(new Driver()))
					->read($request->file('image'))
					->toWebp();

				Storage::put($path, (string) $image);

				$model->value = $path;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}

	/**
	 * Delete model image.
	 */
	public function delete(Setting $model)
	{
		try {
			if (Storage::exists($model->value)) {
				Storage::delete($model->value);
				$model->value = null;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}

	/**
	 * Format file name
	 *
	 * @param Setting $model
	 * @return string Slug
	 */
	public function fileName(Setting $model)
	{
		return Str::slug($model->name, '_');
	}
}
