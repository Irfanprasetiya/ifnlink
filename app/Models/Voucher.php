<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'kategori_id',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function produk_konter()
    {
        return $this->hasMany(ProdukKonter::class);
    }

    public function produk_konter_cabang()
    {
        return $this->hasMany(ProdukKonter::class)->where('cabang_id', auth()->user()->cabang_id);
    }


    // public function produk()
    // {
    //     return $this->belongsTo(Produk::class, 'produk_id');
    // }

}
