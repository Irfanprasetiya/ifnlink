<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'waktu_transaksi',
        'nama_barang',
        'satuan_harga',
        'harga_grosir',
        'qty',
        'total_belanja',
        'keterangan',
        'petugas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

