<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataIklan extends Model
{
    //use Uuid;
    use HasFactory;

    //public $incrementing = false;

    protected $fillable = ['jenis_iklan_id', 'nama_iklan', 'kode_iklan', 'image', 'link'];

    protected $dates = [];

    protected $appends = ['image_url'];

    protected $hidden = [
        'image',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the jenisIklan that owns the DataIklan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenisIklan()
    {
        return $this->belongsTo(JenisIklan::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
