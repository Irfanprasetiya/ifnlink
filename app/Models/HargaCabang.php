<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaCabang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'harga_cabang';

    protected $fillable = [
        'tenant_id',
        'cabang_id',
        'voucher_id',
        'harga_jual',
        'harga_beli',
        'tanggal_mulai',
        'tanggal_berakhir',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'is_active' => 'boolean',
    ];

    // ========== RELATIONSHIPS ==========

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id_tenant');
    }

    // ========== SCOPES ==========

    /**
     * Scope: hanya harga yang aktif (is_active + dalam rentang tanggal)
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_mulai')
                    ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')
                    ->orWhere('tanggal_berakhir', '>=', now());
            });
    }

    /**
     * Scope: filter per cabang
     */
    public function scopeForCabang($query, $cabangId)
    {
        return $query->where('cabang_id', $cabangId);
    }

    /**
     * Scope: filter per tenant
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ========== HELPERS ==========

    /**
     * Cek apakah harga masih berlaku (promo belum kadaluarsa)
     */
    public function isBerlaku(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->tanggal_mulai && $this->tanggal_mulai->isFuture()) {
            return false;
        }

        if ($this->tanggal_berakhir && $this->tanggal_berakhir->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Ambil harga efektif untuk cabang tertentu
     */
    public static function getHarga($cabangId, $voucherId)
    {
        $harga = self::forCabang($cabangId)
            ->where('voucher_id', $voucherId)
            ->aktif()
            ->latest('tanggal_mulai')
            ->first();

        if ($harga) {
            return [
                'harga_jual' => $harga->harga_jual,
                'harga_beli' => $harga->harga_beli,
                'sumber' => 'custom',
                'harga_cabang_id' => $harga->id,
            ];
        }

        $voucher = Voucher::find($voucherId);

        return [
            'harga_jual' => $voucher?->harga_jual ?? 0,
            'harga_beli' => $voucher?->harga_beli ?? 0,
            'sumber' => 'master',
            'harga_cabang_id' => null,
        ];
    }
}