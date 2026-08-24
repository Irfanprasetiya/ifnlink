<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKonter extends Model
{
    use HasFactory;

    protected $table = 'produk_konter';

    protected $fillable = [
        'voucher_id',
        'cabang_id',
        'stok',
        'keterangan',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
    // public function cabang()
    // {
    //     return $this->belongsTo(Cabang::class);
    // }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }


    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }






}
