<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'returs';

    protected $fillable = [
        'kode_retur',
        'penjualan_id',
        'tenant_id',
        'cabang_id',
        'user_id',
        'approved_by',
        'total_refund',
        'alasan',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'total_refund' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(ReturDetail::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
}