<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokHistory extends Model
{
    protected $fillable = [
        'tenant_id',
        'cabang_id',
        'voucher_id',
        'jenis',
        'qty',
        'keterangan',
        'user_id',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}