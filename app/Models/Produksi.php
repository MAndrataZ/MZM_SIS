<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';
    protected $primaryKey = 'id_produksi';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate ID Produksi
            $latest = self::orderBy('id_produksi', 'desc')->first();
            $lastNumber = $latest ? intval(substr($latest->id_produksi, 3)) : 0;
            $model->id_produksi = 'PRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // Set ID pengguna
            $model->id_pengguna = Auth::user()?->id;

            // Tambah stok produk sebelum simpan
            $produk = Produk::find($model->SKU);
            if ($produk) {
                $produk->stok += $model->jumlah_produksi;
                $produk->save();

                // Hitung total modal sebelum insert
                $model->total_modal = $produk->modal * $model->jumlah_produksi;
            }
        });

        // Hapus event created() karena ini yang menyebabkan stok bertambah lagi
        // static::created(function ($model) { ... });
    }

    public function produk() {
        return $this->belongsTo(Produk::class, 'SKU');
    }

    // public function pengguna() {
    //     return $this->belongsTo(User::class, 'id_pengguna');
    // }
}
