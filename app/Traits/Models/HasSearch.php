<?php

namespace App\Traits\Models;

/**
 * Admin model search scopes
 *
 * $q = Model::query();
 * if ($request->filled('search')) { // Not empty
 * 	    $q->searchField('name', $request->input('name'));
 * }
 * if ($request->filled('search')) { // Not empty
 * 	    $q->searchField('email', $request->input('email'));
 * }
 * return $q->latest('id')->paginate(5);
 * return $q->oldest('id')->paginate(5);
 * return $q->orderBy('id')->paginate(5);
 *
 * Case insensitive
 * Model::whereRaw("UPPER('{$column}') LIKE '%'". strtoupper($value)."'%'");
 */
trait HasSearch
{
    public function scopeSearchField($query, $name, $str, $case_insensitive = true)
    {
        if ($case_insensitive) {
            $query->orWhere($name, 'LIKE', "%{$str}%");
        } else {
            $query->orWhere($name, 'like', "%{$str}%");
        }
    }
}
