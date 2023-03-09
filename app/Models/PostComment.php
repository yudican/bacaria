<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'comment', 'status'];

    protected $dates = [];

    /**
     * Get the post that owns the PostComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the PostComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent that owns the PostComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    /**
     * Get all of the children for the PostComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }
}
