<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_toko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained()->cascadeOnDelete();
            $table->foreignId('toko_id')->constrained()->cascadeOnDelete();
            $table->integer('stok_gudang')->default(0);
            $table->integer('stok_packing')->default(0);
            $table->integer('total_stok')->default(0);
            $table->integer('stock_threshold')->default(0);
            $table->timestamps();
            $table->unique(['barang_id', 'toko_id']);
        });

        $anna = DB::table('tokos')->insertGetId([
            'kode_toko' => 'ANNA',
            'nama_toko' => 'ANNA WIFI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('tokos')->insert([
            ['kode_toko' => 'PEL', 'nama_toko' => 'PELAUKAN', 'created_at' => now(), 'updated_at' => now()],
            ['kode_toko' => 'VIL', 'nama_toko' => 'VILLA KENCANA', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $barangs = DB::table('barangs')->get();
        foreach ($barangs as $barang) {
            DB::table('barang_toko')->insert([
                'barang_id' => $barang->id,
                'toko_id' => $anna,
                'stok_gudang' => $barang->stok_gudang,
                'stok_packing' => $barang->stok_packing,
                'total_stok' => $barang->total_stok,
                'stock_threshold' => $barang->stock_threshold,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('barang_masuks', function (Blueprint $table) use ($anna) {
            $table->unsignedBigInteger('toko_id')->default($anna);
            $table->foreign('toko_id')->references('id')->on('tokos')->cascadeOnDelete();
        });

        DB::table('transaksi_keluars')->delete();
        DB::table('transaksis')->delete();

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('kode_toko_inputed');
        });
        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('toko_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['stok_gudang', 'stok_packing', 'total_stok', 'stock_threshold']);
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->integer('stok_gudang')->default(0);
            $table->integer('stok_packing')->default(0);
            $table->integer('total_stok')->default(0);
            $table->integer('stock_threshold')->default(0);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
            $table->string('kode_toko_inputed');
        });

        Schema::table('barang_masuks', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::dropIfExists('barang_toko');

        DB::table('tokos')->whereIn('kode_toko', ['ANNA', 'PEL', 'VIL'])->delete();
    }
};
