<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->foreignId('transaksi_id')->nullable()->constrained('transaksis')->cascadeOnDelete()->after('id');
        });

        DB::transaction(function () {
            $records = DB::table('transaksi_keluars')->whereNull('transaksi_id')->get();
            foreach ($records as $record) {
                $headerId = DB::table('transaksis')->insertGetId([
                    'kode_toko_inputed' => $record->kode_toko_inputed,
                    'user_id' => $record->user_id,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);
                DB::table('transaksi_keluars')
                    ->where('id', $record->id)
                    ->update(['transaksi_id' => $headerId]);
            }
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('kode_toko_inputed');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->string('kode_toko_inputed')->nullable()->after('barang_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('kode_toko_inputed');
        });

        DB::transaction(function () {
            $details = DB::table('transaksi_keluars')->whereNotNull('transaksi_id')->get();
            foreach ($details as $detail) {
                $header = DB::table('transaksis')->where('id', $detail->transaksi_id)->first();
                if ($header) {
                    DB::table('transaksi_keluars')
                        ->where('id', $detail->id)
                        ->update([
                            'kode_toko_inputed' => $header->kode_toko_inputed,
                            'user_id' => $header->user_id,
                        ]);
                }
            }
        });

        Schema::table('transaksi_keluars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transaksi_id');
        });
    }
};
