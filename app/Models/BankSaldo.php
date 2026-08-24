<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSaldo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tanggal', 'saldo', 'tenant_id',];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi tambahan jika ingin akses cabang via user
    public function cabang()
    {
        return $this->hasOneThrough(
            Cabang::class,
            User::class,
            'id',         // foreign key di User (relasi ke User dari BankSaldo)
            'id',         // foreign key di Cabang (relasi ke Cabang dari User)
            'user_id',    // foreign key di BankSaldo
            'cabang_id'   // foreign key di User
        );
    }
}
