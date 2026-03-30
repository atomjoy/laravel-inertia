<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkuResource extends JsonResource
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
			'product_id' => $this->product_id,
			'vat' => $this->vat,
			'packaging_vat' => $this->packaging_vat,
			'price' => $this->price,
			'tax' => $this->tax,
			'sale_price' => $this->sale_price,
			'sale_tax' => $this->sale_tax,
			'packaging_price' => $this->packaging_price,
			'packaging_tax' => $this->packaging_tax,
			'price_decimal' => number_format(($this->price / 100), 2, '.', ','),
			'sale_price_decimal' => number_format(($this->sale_price / 100), 2, '.', ','),
			'packaging_price_decimal' => number_format(($this->packaging_price_decimal / 100), 2, '.', ','),
			'sku' => $this->sku,
			'slug' => $this->slug,
			'name' => $this->name,
			'stock_quantity' => $this->stock_quantity,
			'on_stock' => $this->on_stock,
			'created_at' => (string) $this->created_at,
			'attributes' => AttributeResource::collection($this->attributes),
			'images' => ImageResource::collection($this->images),
			'product' => $this->product,
			'categories' => $this->product->categories,

		];

		// return parent::toArray($request);
	}
}
