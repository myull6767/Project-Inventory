@extends('layouts.app')

@section('title', 'Dashboard — LJN Inventory')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-surface border border-secondary/10 rounded-lg p-6">
        <p class="font-mono text-xs text-primary/50">Total Barang</p>
        <p class="font-mono text-2xl text-secondary mt-1">{{ $totalBarang }}</p>
    </div>
    <div class="bg-surface border border-secondary/10 rounded-lg p-6">
        <p class="font-mono text-xs text-primary/50">Barang Masuk (Bulan Ini)</p>
        <p class="font-mono text-2xl text-secondary mt-1">{{ $totalBarangMasukBulanIni }}</p>
    </div>
    <div class="bg-surface border border-secondary/10 rounded-lg p-6">
        <p class="font-mono text-xs text-primary/50">Transaksi (Bulan Ini)</p>
        <p class="font-mono text-2xl text-secondary mt-1">{{ $totalTransaksiBulanIni }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-surface border border-secondary/10 rounded-lg p-6">
        <h2 class="font-mono text-sm text-secondary mb-4">Stock Alert</h2>
        @if ($stockAlerts->isEmpty())
        <p class="text-xs text-primary/50">Semua stok aman.</p>
        @else
        <ul class="space-y-2">
            @foreach ($stockAlerts as $barang)
            <li class="flex justify-between items-center p-2 bg-tertiary/5 rounded">
                <span class="font-mono text-xs text-primary">{{ $barang->barang?->nama_barang ?? '-' }}</span>
                <span class="font-mono text-xs text-tertiary">{{ $barang->total_stok }}</span>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">
            {{ $stockAlerts->links() }}
        </div>
        @endif
    </div>

    <div class="bg-surface border border-secondary/10 rounded-lg p-6">
        <h2 class="font-mono text-sm text-secondary mb-4">Transaksi Terbaru</h2>
        <div class="space-y-3">
            @foreach ($recentBarangMasuk as $bm)
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-mono text-xs text-primary">{{ $bm->barang->nama_barang ?? '-' }}</span>
                    <span class="font-mono text-[10px] text-primary/40 ml-2">Masuk</span>
                </div>
                <span class="font-mono text-xs text-secondary">{{ $bm->quantity }}</span>
            </div>
            @endforeach
            @foreach ($recentTransaksiKeluar as $tk)
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-mono text-xs text-primary">{{ $tk->barang->nama_barang ?? '-' }}</span>
                    <span class="font-mono text-[10px] text-primary/40 ml-2">Keluar</span>
                </div>
                <span class="font-mono text-xs text-tertiary">{{ $tk->quantity }}</span>
            </div>
            @endforeach
            @if ($recentBarangMasuk->isEmpty() && $recentTransaksiKeluar->isEmpty())
            <p class="text-xs text-primary/50">Belum ada transaksi.</p>
            @endif
        </div>
    </div>
</div>
@endsection
