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

    protected $appends = ['image_path', 'author_name', 'category_name', 'approved_user_name', 'rejected_user_name', 'tags'];

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
}
