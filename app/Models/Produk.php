<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'SKU';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    public function produksi() {
        return $this->hasMany(Produksi::class, 'SKU');
    }

    // public function transaksi() {
    //     return $this->hasMany(Transaksi::class, 'SKU');
    // }
}
