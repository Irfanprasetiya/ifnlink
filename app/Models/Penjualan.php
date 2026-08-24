<?php
// app/Models/Penjualan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'produk_konter_id',
        'qty',
        'harga',
        'harga_grosir',
        'total_harga',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Penjualan.php
    public function produkKonter()
    {
        return $this->belongsTo(\App\Models\ProdukKonter::class, 'produk_konter_id');
    }




}
