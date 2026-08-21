<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'order_id',
        'transaction_id', // ✅ Tambahkan transaction_id
        'jumlah',
        'status',
        'bukti_pembayaran',
        'metode',
        'keterangan',
        'tanggal_bayar',
        'tanggal_konfirmasi',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
    ];

    /**
     * Relasi ke Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    /**
     * Relasi ke Plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    // ✅ Scope untuk memudahkan query
    public function scopeSuccess($query)
    {
        return $query->whereIn('status', ['confirmed', 'settlement', 'capture', 'success']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'expired', 'expire', 'cancel', 'deny']);
    }
}