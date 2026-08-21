<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs'; // Pastikan nama tabel benar

    protected $fillable = [
        'user_id',
        'aksi',
        'modul',
        'deskripsi',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($aksi, $modul = null, $deskripsi = null, $dataLama = null, $dataBaru = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}