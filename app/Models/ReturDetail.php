<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturDetail extends Model
{
    protected $table = 'retur_details';

    protected $fillable = [
        'retur_id',
        'voucher_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}