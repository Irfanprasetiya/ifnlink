<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Voucher (PRODUK UMUM)
 * 
 * CATATAN: Model ini untuk SEMUA produk, bukan hanya voucher.
 * Bisa untuk: alat listrik, kelontong, pulsa, dll.
 */
class Voucher extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'keterangan',
        'tenant_id', // ✅ Multi-tenant
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function produkKonters()
    {
        return $this->hasMany(ProdukKonter::class);
    }
}