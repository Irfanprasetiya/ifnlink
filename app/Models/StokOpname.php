<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    protected $table = 'stok_opnames';

    protected $fillable = [
        'kode_opname',
        'tenant_id',
        'cabang_id',
        'user_id',
        'tanggal_opname',
        'catatan',
    ];

    protected $casts = [
        'tanggal_opname' => 'date',
    ];

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