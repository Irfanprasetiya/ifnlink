<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran_kas'; // WAJIB

    protected $fillable = [
        'tanggal',
        'akun_pengeluaran_id',
        'cabang_id',
        'nominal',
        'keterangan',
        'tenant_id'
    ];

    public function akun()
    {
        return $this->belongsTo(
            AkunPengeluaran::class,
            'akun_pengeluaran_id'
        );
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

}

