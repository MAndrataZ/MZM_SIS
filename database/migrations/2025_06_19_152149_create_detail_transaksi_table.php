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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi', 10);
            $table->string('SKU', 10);
            $table->string('nama_produk', 50);
            $table->enum('dijual_ke', ['retail', 'reseller']);
            $table->double('harga_satuan', 20, 2);
            $table->integer('jumlah');
            $table->double('total', 20, 2);
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('SKU')->references('SKU')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
