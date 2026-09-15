<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'order_id',
        'transaction_id',
        'jumlah',
        'status',
        'bukti_pembayaran',
        'metode',
        'keterangan',
        'tanggal_bayar',
        'tanggal_konfirmasi',
        'verified_by',
        'verified_at',
        'catatan_admin',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========

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

    /**
     * Relasi ke User yang verifikasi (admin)
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ========== SCOPES (MIDTRANS) ==========

    /**
     * Scope: pembayaran sukses
     */
    public function scopeSuccess($query)
    {
        return $query->whereIn('status', ['confirmed', 'settlement', 'capture', 'success']);
    }

    /**
     * Scope: pembayaran pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: pembayaran gagal
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'expired', 'expire', 'cancel', 'deny']);
    }

    // ========== SCOPES (QRIS MANUAL) ==========

    /**
     * Scope: pembayaran via QRIS Manual
     */
    public function scopeQrisManual($query)
    {
        return $query->where('metode', 'qris_manual');
    }

    /**
     * Scope: QRIS Manual pending (belum upload bukti)
     */
    public function scopeQrisPending($query)
    {
        return $query->where('metode', 'qris_manual')
            ->where('status', 'pending');
    }

    /**
     * Scope: QRIS Manual menunggu verifikasi admin
     */
    public function scopeQrisMenungguVerifikasi($query)
    {
        return $query->where('metode', 'qris_manual')
            ->where('status', 'menunggu_verifikasi');
    }

    /**
     * Scope: QRIS Manual yang sudah approved
     */
    public function scopeQrisApproved($query)
    {
        return $query->where('metode', 'qris_manual')
            ->where('status', 'confirmed');
    }

    /**
     * Scope: QRIS Manual yang ditolak
     */
    public function scopeQrisRejected($query)
    {
        return $query->where('metode', 'qris_manual')
            ->where('status', 'rejected');
    }

    // ========== HELPERS ==========

    /**
     * Cek apakah pembayaran ini via QRIS Manual
     */
    public function isQrisManual(): bool
    {
        return $this->metode === 'qris_manual';
    }

    /**
     * Cek apakah pembayaran ini via Midtrans
     */
    public function isMidtrans(): bool
    {
        return in_array($this->metode, [
            'midtrans',
            'bank_transfer',
            'qris',
            'cstore',
            'gopay',
            'shopeepay',
            'credit_card',
        ]);
    }

    /**
     * Cek apakah bisa upload bukti (QRIS Manual)
     */
    public function canUploadBukti(): bool
    {
        return $this->isQrisManual()
            && in_array($this->status, ['pending', 'rejected']);
    }

    /**
     * Generate Order ID QRIS Manual
     * Format: QRIS-{tenant_id}-{timestamp}-{random6}
     * Contoh: QRIS-10-1692145800-AB12CD
     */
    public static function generateOrderIdQrisManual($tenantId): string
    {
        return 'QRIS-' . $tenantId . '-' . time() . '-' . strtoupper(Str::random(6));
    }

    /**
     * Label status untuk tampilan
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Sedang Diverifikasi',
            'confirmed', 'settlement', 'capture', 'success' => 'Lunas',
            'rejected' => 'Ditolak',
            'cancelled', 'cancel' => 'Dibatalkan',
            'expired', 'expire' => 'Kadaluarsa',
            'failed', 'deny' => 'Gagal',
            default => ucfirst($this->status),
        };
    }

    /**
     * Warna status untuk badge
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'menunggu_verifikasi' => 'blue',
            'confirmed', 'settlement', 'capture', 'success' => 'emerald',
            'rejected', 'failed', 'deny' => 'rose',
            'cancelled', 'cancel' => 'slate',
            'expired', 'expire' => 'slate',
            default => 'slate',
        };
    }

    /**
     * Icon status untuk tampilan
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'pending' => '⏳',
            'menunggu_verifikasi' => '🔍',
            'confirmed', 'settlement', 'capture', 'success' => '✅',
            'rejected', 'failed', 'deny' => '❌',
            'cancelled', 'cancel' => '🚫',
            'expired', 'expire' => '⏰',
            default => '❓',
        };
    }

    /**
     * Cek apakah status sukses (untuk method lain)
     */
    public function isSuccess(): bool
    {
        return in_array($this->status, ['confirmed', 'settlement', 'capture', 'success']);
    }

    /**
     * Cek apakah status pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}