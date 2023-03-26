<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'ip_address', 'user_agent', 'referer'];

    protected $dates = [];

    /**
     * Get the post that owns the PostLike
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class)->whereStatus('publish')->wherePublishStatus('published');
    }

    /**
     * Get the user that owns the PostLike
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
