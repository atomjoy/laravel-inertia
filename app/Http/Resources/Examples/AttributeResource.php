<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		// return parent::toArray($request);

		return [
			'id' => $this->id,
			'product_id' => $this->product_id,
			'name' => $this->name,
			'picker' => $this->picker,
			'property' => new PropertyResource($this->pivot->property),
			// 'pivot' => new AttributeSkuResource($this->pivot),
		];
	}
}
