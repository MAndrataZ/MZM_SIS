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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_transaksi', 10)->primary();
            $table->dateTime('tanggal_transaksi');
            $table->integer('produk_terjual');
            $table->double('total_pendapatan', 20, 2);
            $table->string('id_pengguna', 10);
            $table->timestamps();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
