<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Transaksi;
use App\Models\TransaksiKeluar;

class DashboardController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();

        $stockAlerts = $barangs->filter(fn ($b) => $b->total_stok <= $b->stock_threshold);

        $recentBarangMasuk = BarangMasuk::with('barang', 'supplier')
            ->latest()
            ->take(5)
            ->get();

        $recentTransaksiKeluar = TransaksiKeluar::with('barang')
            ->latest()
            ->take(10)
            ->get();

        $totalBarang = $barangs->count();
        $totalBarangMasukBulanIni = BarangMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalTransaksiBulanIni = Transaksi::whereMonth('created_at', now()->month)
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
