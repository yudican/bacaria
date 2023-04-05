<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'parent_id', 'comment', 'status'];

    protected $dates = [];

    protected $appends = ['user_name', 'user_image', 'time_ago', 'date_created'];

    protected $hidden = [
        'user_id',
        'post_id',
        'updated_at',
    ];

    protected $with = ['childrens'];

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
        return $this->belongsTo(PostComment::class, 'parent_id')->where('parent_id', null)->with('parent');
    }

    /**
     * Get all of the children for the PostComment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->with('childrens');
    }

    public function getUserNameAttribute()
    {
        $user = User::find($this->user_id);

        return $user->name ?? 'Member';
    }

    public function getUserImageAttribute()
    {
        $user = User::find($this->user_id);

        return $user->profile_photo_path ?? $user->profile_photo_url;
    }

    public function getTimeAgoAttribute()
    {
        // get hours created
        $hours = $this->created_at->diffInHours(now());

        if ($hours < 1) {
            return $this->created_at->diffInMinutes(now()) . ' menit';
        } else {
            if ($hours < 24) {
                return $hours . ' jam';
            } else {
                return $this->created_at->format('d M Y');
            }
        }
    }

    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}
