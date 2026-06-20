<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
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
            ->take(5)
            ->get();

        $totalBarang = $barangs->count();
        $totalBarangMasukBulanIni = BarangMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalTransaksiBulanIni = TransaksiKeluar::whereMonth('created_at', now()->month)
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
