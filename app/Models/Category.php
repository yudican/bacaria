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

    protected $appends = ['image_url', 'layout_name'];

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
        return $this->hasMany(Post::class)->limit(10)->orderBy('created_at', 'desc');
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

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getLayoutNameAttribute()
    {
        $layout = Layout::find($this->layout_id);
        return $layout ? $layout->name : null;
    }
}
