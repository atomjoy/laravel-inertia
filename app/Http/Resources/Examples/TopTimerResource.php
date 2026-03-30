<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopTimerResource extends JsonResource
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
			'endtime' => (string) $this->endtime,
			'text_font' => $this->text_font,
			'text_size' => $this->text_size,
			'text_weight' => $this->text_weight,
			'text_color' => $this->text_color,
			'animation' => $this->animation,
			'created_at' => (string) $this->created_at,
		];

		// return parent::toArray($request);
	}
}
