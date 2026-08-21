<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['nama_paket', 'slug', 'harga', 'max_user', 'fitur', 'is_active'];

    protected $casts = [
        'fitur' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Pembayaran (Optional)
     */
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'plan_id', 'id');
    }

    /**
     * Relasi ke Tenant (Optional)
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'plan_id', 'id');
    }
}