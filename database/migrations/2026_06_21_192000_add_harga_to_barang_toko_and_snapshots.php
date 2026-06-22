<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_toko', function (Blueprint $table) {
            $table->integer('harga')->default(0);
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->integer('harga_snapshot')->default(0);
        });

        DB::table('barang_toko')->update(['harga' => 0]);
    }

    public function down(): void
    {
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropColumn('harga_snapshot');
        });

        Schema::table('barang_toko', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
