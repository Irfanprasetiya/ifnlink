<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['nama_bank', 'tenant_id'];

    public function transaksis()
    {
        return $this->hasMany(TransaksiBank::class);
    }

    public function transaksi_banks()
    {
        return $this->hasMany(TransaksiBank::class);
    }


    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }



}
