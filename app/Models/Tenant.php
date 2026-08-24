<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_tenant';

    protected $fillable = [
        'nama_toko',
        'nama_pemilik',
        'email',
        'no_hp',
        'domain',
        'plan_id',
        'status_langganan',
        'status_data',
        'tanggal_berakhir',
        'max_user',
        'churned_at',
        'churn_reason',
        'deleted_by',
        'delete_reason',
    ];

    protected $casts = [
        'tanggal_berakhir' => 'datetime',
        'churned_at' => 'datetime',
    ];

    // Relasi
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id', 'id_tenant');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiBank::class, 'tenant_id', 'id_tenant');
    }

    public function cabang()
    {
        return $this->hasMany(Cabang::class, 'tenant_id', 'id_tenant');
    }

    // Cek limit transaksi
    public function canAddTransaction()
    {
        if (!$this->plan || $this->plan->harga > 0) {
            return true;
        }

        $todayCount = $this->transaksi()
            ->whereDate('waktu_transaksi', today())
            ->count();

        return $todayCount < 20; // 10 transaksi x 2 record
    }

    // Sisa transaksi
    public function getSisaTransaksiAttribute()
    {
        if (!$this->plan || $this->plan->harga > 0) {
            return 'Unlimited';
        }

        $todayCount = $this->transaksi()
            ->whereDate('waktu_transaksi', today())
            ->count();

        return max(0, 10 - floor($todayCount / 2));
    }

    // Cek bisa tambah user
    public function canAddUser()
    {
        $maxUser = $this->max_user ?? 5;
        return $this->users()->count() < $maxUser;
    }

    // Cek fitur laba/rugi
    public function hasLabaRugiFeature()
    {
        return $this->plan && $this->plan->harga > 0;
    }

    // Owner user
    public function getOwnerUserAttribute()
    {
        return $this->users()->where('role', 'super_admin')->first();
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('status_langganan', 'active');
    }

    public function isLocked(): bool
    {
        if ($this->status_langganan === 'pending' || $this->status_langganan === 'suspended') {
            return true;
        }

        if ($this->status_langganan === 'active' && $this->tanggal_berakhir && now()->greaterThan($this->tanggal_berakhir)) {
            return true;
        }

        return false;
    }
}