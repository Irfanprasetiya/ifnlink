<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBank extends Model
{
    use HasFactory;

    // App\Models\TransaksiBank.php
    protected $guarded = ['id'];
    protected $casts = [
        'is_saldo_awal' => 'boolean',
    ];
    protected $fillable = [
        'tenant_id',
        'cabang_id',
        'user_id',
        'bank_id',
        'jenis_transaksi_id',
        'akun_pengeluaran_id', // ✅ wajib
        'no_tujuan',
        'nominal',
        'bayar',
        'debit',
        'kredit',
        'keterangan',
        'waktu_transaksi',
        'is_saldo_awal', // ✅ tambah ini
        'saldo_akhir', // ✅ tambah ini
    ];

    // Relasi ke Pemilik (Tenant)
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }




    // debit kredit
    public static function getSaldo($bank_id)
    {
        return self::where('bank_id', $bank_id)
            ->selectRaw('SUM(debit) - SUM(kredit) as saldo')
            ->value('saldo') ?? 0;


    }

    public function akunPengeluaran()
    {
        return $this->belongsTo(AkunPengeluaran::class, 'akun_pengeluaran_id');
    }


    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function jenis_transaksi()
    {
        return $this->belongsTo(JenisTransaksi::class);
    }

    public function jenisTransaksi()
    {
        return $this->belongsTo(
            \App\Models\JenisTransaksi::class,
            'jenis_transaksi_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }




}
