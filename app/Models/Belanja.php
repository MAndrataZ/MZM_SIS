<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class Belanja extends Model
{
    protected $table = 'belanja';
    protected $primaryKey = 'id_belanja';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Auto generate ID Belanja
            $latest = self::orderBy('id_belanja', 'desc')->first();
            $lastNumber = $latest ? intval(substr($latest->id_belanja, 3)) : 0;
            $model->id_belanja = 'BLJ' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // Ambil dari user yang login
            $model->id_pengguna = Auth::user()?->id_pengguna;
        });
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // public function pengguna() {
    //     return $this->belongsTo(User::class, 'id_pengguna');
    // }
}
