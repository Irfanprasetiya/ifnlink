<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $fillable = ['produk_konter_id', 'qty', 'tanggal'];

    public function produk_konter()
    {
        return $this->belongsTo(ProdukKonter::class);
    }

}

