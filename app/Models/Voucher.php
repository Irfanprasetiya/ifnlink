<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HargaCabang;

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

    /**
     * Ambil harga jual untuk cabang tertentu
     * Kalau tidak ada custom → fallback ke harga master
     */
    public function hargaJualUntukCabang($cabangId)
    {
        $hargaCabang = HargaCabang::forCabang($cabangId)
            ->where('voucher_id', $this->id)
            ->aktif()
            ->latest('tanggal_mulai')
            ->first();

        return $hargaCabang ? $hargaCabang->harga_jual : $this->harga_jual;
    }

    /**
     * Ambil harga beli untuk cabang tertentu
     */
    public function hargaBeliUntukCabang($cabangId)
    {
        $hargaCabang = HargaCabang::forCabang($cabangId)
            ->where('voucher_id', $this->id)
            ->aktif()
            ->latest('tanggal_mulai')
            ->first();

        return $hargaCabang && $hargaCabang->harga_beli
            ? $hargaCabang->harga_beli
            : $this->harga_beli;
    }

    /**
     * Cek apakah produk punya harga custom di cabang ini
     */
    public function hasHargaCustom($cabangId)
    {
        return HargaCabang::forCabang($cabangId)
            ->where('voucher_id', $this->id)
            ->aktif()
            ->exists();
    }

    /**
     * Relasi ke harga cabang
     */
    public function hargaCabangs()
    {
        return $this->hasMany(HargaCabang::class, 'voucher_id');
    }
}