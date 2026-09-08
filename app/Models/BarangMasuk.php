<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'produk_konter_id',
        'qty',
        'tanggal',
        'tenant_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function produk_konter()
    {
        return $this->belongsTo(ProdukKonter::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}