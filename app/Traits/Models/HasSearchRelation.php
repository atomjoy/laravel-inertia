<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Model search scopes relation
 *
 * $authors = Author::with(['books' => fn($query) => $query->where('title', 'LIKE', 'PHP%')])
 *
 * $query->whereHas('books', fn($query) =>
 * 		$query->where('title', 'LIKE', 'PHP%')
 * )->get();
 *
 * if (request()->filled('author')) {
 *      $q->whereHas('author', fn($query) => $query->where('name', 'LIKE', request()->input('author') . '%'));
 * }
 */
trait HasSearchRelation
{
    /**
     * Scope relations
     *
     * Author::withWhereHas('books', fn($query) =>
     *  	$query->where('title', 'LIKE', 'PHP%')
     * )->get();
     */
    public function scopeWithWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)
            ->with([$relation => $constraint]);
    }

    /**
     * Search with admin relation
     *
     * @param Builder $query
     * @param String $str
     * @return void
     */
    public function scopeSearchAdmin($query, $str)
    {
        $query->orWhereHas('admin', function ($q) use ($str) {
            $q->where('name', 'LIKE', "%{$str}%");
        });
    }
}
