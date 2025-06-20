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
        Schema::create('produksi', function (Blueprint $table) {
            $table->string('id_produksi', 10)->primary();
            $table->string('SKU', 10);
            $table->unsignedBigInteger('id_pengguna');
            $table->string('nama_produk', 50);
            $table->integer('stok');
            $table->dateTime('tanggal_produksi');
            $table->integer('jumlah_produksi');
            $table->double('total_modal', 20, 2);
            $table->timestamps();

            $table->foreign('SKU')->references('SKU')->on('produk');
            $table->foreign('id_pengguna')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
