<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangToko;
use App\Models\Transaksi;
use App\Models\TransaksiKeluar;

class DashboardController extends Controller
{
    public function index()
    {
        $tokoId = session('toko_id');

        $stockAlerts = BarangToko::with('barang')
            ->where('toko_id', $tokoId)
            ->whereColumn('total_stok', '<=', 'stock_threshold')
            ->simplePaginate(10);

        $recentBarangMasuk = BarangMasuk::with('barang', 'supplier')
            ->where('toko_id', $tokoId)
            ->latest()
            ->take(5)
            ->get();

        $recentTransaksiKeluar = TransaksiKeluar::with('barang')
            ->whereHas('transaksi', fn ($q) => $q->where('toko_id', $tokoId))
            ->latest()
            ->take(10)
            ->get();

        $totalBarang = BarangToko::where('toko_id', $tokoId)->count();
        $totalBarangMasukBulanIni = BarangMasuk::where('toko_id', $tokoId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalTransaksiBulanIni = Transaksi::where('toko_id', $tokoId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('dashboard', compact(
            'stockAlerts',
            'recentBarangMasuk',
            'recentTransaksiKeluar',
            'totalBarang',
            'totalBarangMasukBulanIni',
            'totalTransaksiBulanIni',
        ));
    }
}
