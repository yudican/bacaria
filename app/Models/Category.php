<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['name', 'slug', 'image', 'layout_id'];

    protected $dates = [];

    /**
     * Get all of the bannerimage for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banners()
    {
        return $this->hasMany(BannerCategory::class);
    }

    /**
     * Get all of the posts for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the layout that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }
}
