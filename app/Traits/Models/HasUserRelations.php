<?php

namespace App\Traits\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User model relations
 */
trait HasUserRelations
{
    /**
     * Get posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
