<?php

// app/Models/JenisTransaksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    protected $fillable = ['nama_transaksi', 'keterangan', 'tenant_id'];
}

