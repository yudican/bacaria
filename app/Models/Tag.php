<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'uid_tag'
    ];

    protected $dates = [];

    // with count
    protected $withCount = ['posts'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get all of the posts for the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tags', 'tag_id', 'post_id');
    }
}
