<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';

    protected $fillable = [
        'nama_cabang',
        'alamat_cabang',
        'keterangan',
        'tenant_id',
        'is_default',
        'telepon',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ============================================
    // RELASI
    // ============================================

    // Relasi dengan table user (semua user di cabang ini)
    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    // Relasi dengan tenant (pemilik cabang)
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // Relasi dengan akun (user yang punya akses ke cabang ini)
    public function akuns()
    {
        return $this->hasMany(User::class, 'cabang_id', 'id');
    }

    // Relasi dengan transaksi bank
    public function transaksis()
    {
        return $this->hasMany(TransaksiBank::class, 'cabang_id');
    }

    // ============================================
    // SCOPE
    // ============================================

    // Scope untuk exclude cabang default (Gudang)
    public function scopeNotDefault($query)
    {
        return $query->where('is_default', false);
    }

    // Scope untuk hanya cabang default (Gudang)
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Scope untuk cabang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk tenant tertentu
    public function scopeTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ============================================
    // ATTRIBUTE
    // ============================================

    // Cek apakah ini cabang default (Gudang)
    // public function isDefault()
    // {
    //     return $this->is_default === true;
    // }

    // Get jumlah user di cabang ini
    public function getJumlahUserAttribute()
    {
        return $this->users()->count();
    }

    // Get jumlah transaksi hari ini
    public function getTransaksiHariIniAttribute()
    {
        return $this->transaksis()
            ->whereDate('waktu_transaksi', now()->toDateString())
            ->count();
    }

    // ============================================
    // BOOT METHOD - PROTEKSI CABANG DEFAULT
    // ============================================

    // protected static function boot()
    // {
    //     parent::boot();

    //     // Cegah hapus cabang default (Gudang)
    //     static::deleting(function ($cabang) {
    //         if ($cabang->is_default) {
    //             throw new \Exception('Cabang default (Gudang) tidak dapat dihapus!');
    //         }

    //         // Cek apakah masih ada user di cabang ini
    //         if ($cabang->users()->count() > 0) {
    //             throw new \Exception('Cabang masih memiliki ' . $cabang->users()->count() . ' user. Pindahkan user terlebih dahulu.');
    //         }
    //     });

    //     // Cegah ubah nama cabang default
    //     static::updating(function ($cabang) {
    //         if ($cabang->is_default) {
    //             // Hanya boleh ubah alamat & telepon
    //             if ($cabang->isDirty('nama_cabang')) {
    //                 throw new \Exception('Nama cabang default (Gudang) tidak dapat diubah!');
    //             }
    //             if ($cabang->isDirty('status')) {
    //                 throw new \Exception('Status cabang default (Gudang) tidak dapat diubah!');
    //             }
    //         }
    //     });

    //     // Auto-set status untuk cabang baru
    //     static::creating(function ($cabang) {
    //         if (!$cabang->status) {
    //             $cabang->status = 'aktif';
    //         }
    //         if (!$cabang->is_default) {
    //             $cabang->is_default = false;
    //         }
    //     });
    // }

    // ============================================
    // METHOD HELPER
    // ============================================

    // Cek apakah cabang bisa diedit
    public function canEdit()
    {
        return !$this->is_default;
    }

    // Cek apakah cabang bisa dihapus
    public function canDelete()
    {
        return !$this->is_default && $this->users()->count() === 0;
    }

    // Get badge HTML untuk tampilan
    public function getBadgeHtml()
    {
        if ($this->is_default) {
            return '<span class="text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400 px-2 py-0.5 rounded-full font-bold">DEFAULT</span>';
        }

        return '<span class="text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400 px-2 py-0.5 rounded-full font-bold">' . strtoupper($this->status) . '</span>';
    }
}