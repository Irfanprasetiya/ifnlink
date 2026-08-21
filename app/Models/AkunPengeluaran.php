<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunPengeluaran extends Model
{
    protected $fillable = [
        'datetime',
        'nama_akun',
        'keterangan',
        'tenant_id'
    ];
}
