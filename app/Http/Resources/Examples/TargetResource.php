<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetResource extends JsonResource
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
			'amount' => number_format($this->amount / 100, 2, '.', ''),
			'period' => $this->period,
			'bar_bg' => $this->bar_bg,
			'bar_color' => $this->bar_color,
			'bar_opacity' => $this->bar_opacity,
			'bar_radius' => $this->bar_radius,
			'bar_border' => $this->bar_border,
			'text_color' => $this->text_color,
			'text_font' => $this->text_font,
			'text_size' => $this->text_size,
			'text_weight' => $this->text_weight,
			'text_align' => $this->text_align,
			'animation' => $this->animation,
			'created_at' => (string) $this->created_at,
		];

		// return parent::toArray($request);
	}
}
