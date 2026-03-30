<?php

namespace App\Upload;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingFile
{
	/**
	 * Update model file.
	 */
	public function upload($request, Setting $model)
	{
		try {
			if ($request->file('file_zip')) {
				$path = '/media/settings/' . $this->fileName($model) . '.zip';
				Storage::putFileAs('/media/settings', $request->file('file_zip'), $this->fileName($model) . '.zip');
				$model->value = $path;
				$model->save();
			}

			if ($request->file('file_pdf')) {
				$path = '/media/settings/' . $this->fileName($model) . '.pdf';
				Storage::putFileAs('/media/settings', $request->file('file_pdf'), $this->fileName($model) . '.pdf');
				$model->value = $path;
				$model->save();
			}

			if ($request->file('file_mp3')) {
				$path = '/media/settings/' . $this->fileName($model) . '.mp3';
				Storage::putFileAs('/media/settings', $request->file('file_mp3'), $this->fileName($model) . '.mp3');
				$model->value = $path;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}

	/**
	 * Delete model file.
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
