<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id', // ✅ Multi-tenant
        'cabang_id', // ✅ Multi-cabang
        'produk_konter_id',
        'kode_transaksi',
        'qty',
        'harga',
        'harga_grosir',
        'total_harga',
        'bayar', // ✅
        'kembalian', // ✅
        'status', // ✅
        'diskon', // ✅
        'total_setelah_diskon', // ✅
        'keterangan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_grosir' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_setelah_diskon' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function produkKonter()
    {
        return $this->belongsTo(ProdukKonter::class, 'produk_konter_id');
    }

    // public function details()
    // {
    //     return $this->hasMany(PenjualanDetail::class);
    // }
    // app/Models/Penjualan.php
    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

}