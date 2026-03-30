<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'title' => $this->title,
			'url' => $this->url,
			'website' => $this->website,
			'is_visible' => $this->is_visible,
			'created_at' => (string) $this->created_at,
		];

		// return parent::toArray($request);
	}
}
