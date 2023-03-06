<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataHalaman extends Model
{
    //use Uuid;
    use HasFactory;

    protected $table = 'data_halaman';

    //public $incrementing = false;

    protected $fillable = ['category_id', 'judul', 'slug', 'banner', 'isi'];

    protected $dates = [];

    /**
     * Get the category that owns the DataHalaman
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
