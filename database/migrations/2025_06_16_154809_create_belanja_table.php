<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('belanja', function (Blueprint $table) {
            $table->string('id_belanja', 10)->primary();
            $table->string('id_barang', 10);
            $table->string('id_pengguna', 10);
            $table->string('nama_barang', 50);
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('total');
            $table->dateTime('tanggal_beli');
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang');
           // $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belanja');
    }
};
