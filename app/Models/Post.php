<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = [
        'approved_user_id', 'author_id', 'category_id', 'content',  'editor', 'image', 'rejected_user_id', 'slug', 'status', 'title', 'type', 'uid_post', 'comment_status',
        'publish_status',
        'reject_reason', 'caption'
    ];

    protected $dates = [];

    protected $appends = ['image_path', 'author_image', 'author_name', 'category_name', 'approved_user_name', 'rejected_user_name', 'tags', 'time_ago'];

    protected $hidden = [
        'category_id',
        'author_id',
        'approved_user_id',
        'rejected_user_id',
        'image',
        'reject_reason',
        'updated_at',
        'approved_user_name',
        'rejected_user_name',
    ];

    // with count
    protected $withCount = ['likes', 'comments'];

    /**
     * Get all of the postTags for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postTags()
    {
        return $this->hasMany(PostTag::class);
    }

    /**
     * Get all of the comments for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    /**
     * Get all of the likes for the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get the category that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approved_user()
    {
        return $this->belongsTo(User::class, 'approved_user_id');
    }

    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rejected_user()
    {
        return $this->belongsTo(User::class, 'rejected_user_id');
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('assets/img/placeholder.svg');
    }

    public function getAuthorNameAttribute()
    {
        $user = User::find($this->author_id);
        return $user ? $user->name : '-';
    }

    public function getAuthorImageAttribute()
    {
        $user = User::find($this->author_id);
        return $user ? $user->profile_photo_url : '-';
    }

    public function getCategoryNameAttribute()
    {
        $category = Category::find($this->category_id);
        return $category ? $category->name : '-';
    }

    public function getApprovedUserNameAttribute()
    {
        $user = User::find($this->approved_user_id);
        return $user ? $user->name : '-';
    }

    public function getRejectedUserNameAttribute()
    {
        $user = User::find($this->rejected_user_id);
        return $user ? $user->name : '-';
    }


    public function getTagsAttribute()
    {
        $tags = PostTag::where('post_id', $this->id)->implode('name', ',');
        return $tags;
    }

    public function getTimeAgoAttribute()
    {
        // get hours created
        $hours = $this->created_at->diffInHours(now());

        if ($hours < 24) {
            return $hours . ' jam';
        } else {
            return $this->created_at->format('d M Y');
        }
    }
}
