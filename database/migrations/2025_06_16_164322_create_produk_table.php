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
        Schema::create('produk', function (Blueprint $table) {
            $table->string('SKU', 10)->primary();
            $table->string('gambar', 100)->nullable();
            $table->string('nama_produk', 50);
            $table->integer('netto');
            $table->string('satuan', 10);
            $table->double('modal', 20, 2);
            $table->double('harga_reseller', 20, 2);
            $table->double('harga_retail', 20, 2);
            $table->integer('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
