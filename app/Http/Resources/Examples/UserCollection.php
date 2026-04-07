<?php

namespace App\Http\Resources;

use App\Http\Resources\PaginationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'data' => $this->collection,
			'paginate' => new PaginationResource($this),
			// Mapping ideas
			// 'data' => $this->collection->map(function ($resource) {
			//  $resource->additional(['can' => ['update_user' => Auth::user()->can('update', $resource)]]);
			// 	$resource['can'] = ['update_user' => Auth::user()->can('update', $resource)];
			// 	return new UserResource($resource);
			// }),
		];

		return parent::toArray($request);
	}
}
