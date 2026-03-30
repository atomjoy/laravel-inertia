<?php

namespace App\Upload;

use App\Models\Audio;
use Illuminate\Support\Facades\Storage;

class AudioMp3
{
	/**
	 * Update model path.
	 */
	public function upload($request, Audio $model)
	{
		try {
			if ($request->file('audio')) {
				$path = '/media/mp3/' . $model->id . '.mp3';

				Storage::putFileAs('/media/mp3', $request->file('audio'), $model->id . '.mp3');

				$model->path = $path;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}

	/**
	 * Delete model path.
	 */
	public function delete(Audio $model)
	{
		try {
			if (Storage::exists($model->path)) {
				Storage::delete($model->path);
				$model->path = null;
				$model->save();
			}
		} catch (\Throwable $e) {
			report($e->getMessage());
		}
	}
}
