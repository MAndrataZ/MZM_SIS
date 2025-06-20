<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Auto generate ID Transaksi
            $latest = self::orderBy('id_transaksi', 'desc')->first();
            $lastNumber = $latest ? intval(substr($latest->id_transaksi, 3)) : 0;
            $model->id_transaksi = 'TRX' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // Ambil dari user yang login
            $model->id_pengguna = Auth::user()?->id_pengguna;
        });
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }
}
