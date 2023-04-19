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

    protected $fillable = ['jenis_iklan_id', 'nama_iklan', 'kode_iklan', 'image', 'link', 'ads_slot_id', 'ads_client_id'];

    protected $dates = [];

    protected $appends = ['image_url', 'kode_jenis_iklan'];

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

    public function getKodeJenisIklanAttribute()
    {
        return $this->jenisIklan ? $this->jenisIklan->kode_jenis_iklan : null;
    }
}
