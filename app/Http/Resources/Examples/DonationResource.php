<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
			'name' => $this->name,
			'email' => $this->email,
			'message' => $this->message,
			'amount' => number_format($this->amount / 100, 2, '.', ''),
			'currency' => $this->currency,
			'last_name' => $this->last_name,
			'phone' => $this->phone,
			'gateway' => $this->gateway,
			'status' => $this->status,
			'payment_id' => $this->payment_id,
			'external_id' => $this->external_id,
			'is_seen' => $this->is_seen,
			'gif' => $this->gif,
			'url' => $this->url,
			'ip' => $this->ip,
			// 'created_at' => (string) $this->created_at,
			'created_at' => (string) $this->created_at->format('M d, Y, H:m'),
		];

		// return parent::toArray($request);
	}
}
