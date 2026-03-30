<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopDonationResource extends JsonResource
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
			'show' => $this->show,
			'show_numbers' => $this->show_numbers,
			'period' => $this->period,
			'text_font' => $this->text_font,
			'text_size' => $this->text_size,
			'text_weight' => $this->text_weight,
			'text_align' => $this->text_align,
			'text_color' => $this->text_color,
			'text_color_id' => $this->text_color_id,
			'text_color_amount' => $this->text_color_amount,
			'animation' => $this->animation,
			'created_at' => (string) $this->created_at,
		];

		// return parent::toArray($request);
	}
}
