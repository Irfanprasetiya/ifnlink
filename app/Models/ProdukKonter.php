<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ProdukKonter (STOK PRODUK PER CABANG)
 */
class ProdukKonter extends Model
{
    protected $table = 'produk_konter'; // ✅ Nama tabel tanpa 's'

    protected $fillable = [
        'voucher_id', // Produk ID
        'cabang_id',
        'tenant_id', // ✅ Multi-tenant
        'stok',
        'keterangan',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}