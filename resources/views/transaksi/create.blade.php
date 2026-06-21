@extends('layouts.app')

@section('title', 'Transaksi — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Transaksi Barang Keluar</h1>

    @if (session('warning'))
    <div class="mb-6 p-4 bg-tertiary/10 border border-tertiary/30 rounded-md text-sm text-primary">
        {{ session('warning') }}
    </div>
    @endif

    <form method="POST" action="{{ route('transaksi.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf

        <div>
            <label for="barang_search" class="font-mono text-xs text-primary/70 block mb-1.5">Barang</label>
            <input type="text" id="barang_search" autocomplete="off" required
                placeholder="Ketik kode atau nama barang..."
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
            <input type="hidden" name="barang_id" id="barang_id" value="{{ old('barang_id') }}">
            <div id="barang-dropdown" class="hidden mt-1 border border-primary/20 rounded-md bg-surface shadow-lg max-h-48 overflow-y-auto"></div>
            <div id="stok-info" class="font-mono text-xs text-primary/50 mt-1"></div>
        </div>

<script id="barangs-data" type="application/json">@json($barangsJson)</script>

        <div>
            <label for="quantity" class="font-mono text-xs text-primary/70 block mb-1.5">Jumlah</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required min="1"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label class="font-mono text-xs text-primary/70 block mb-1.5">Kode Toko</label>
            <select id="kode_toko_select"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">— Pilih Toko —</option>
                @foreach ($tokos as $toko)
                <option value="{{ $toko->kode_toko }}">{{ $toko->kode_toko }} — {{ $toko->nama_toko }}</option>
                @endforeach
            </select>
            <input type="text" id="kode_toko_inputed" name="kode_toko_inputed" value="{{ old('kode_toko_inputed') }}" required autocomplete="off"
                placeholder="Contoh: TKO-01 atau TKO-01-2"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary mt-2">
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Simpan Transaksi
        </button>
    </form>
</div>

@vite(['resources/js/transaksi.js'])
@endsection
