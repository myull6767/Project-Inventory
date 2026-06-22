@extends('layouts.app')

@section('title', 'Edit Barang — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Edit Barang</h1>

    <form method="POST" action="{{ route('barangs.update', $barang) }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="kode_barang" class="font-mono text-xs text-primary/70 block mb-1.5">Kode Barang</label>
            <input type="text" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="nama_barang" class="font-mono text-xs text-primary/70 block mb-1.5">Nama Barang</label>
            <input type="text" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="stok_gudang" class="font-mono text-xs text-primary/70 block mb-1.5">Stok Gudang</label>
                <input type="number" id="stok_gudang" name="stok_gudang" value="{{ old('stok_gudang', $stok->stok_gudang ?? 0) }}" min="0"
                    class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
            </div>
            <div>
                <label for="stok_packing" class="font-mono text-xs text-primary/70 block mb-1.5">Stok Packing</label>
                <input type="number" id="stok_packing" name="stok_packing" value="{{ old('stok_packing', $stok->stok_packing ?? 0) }}" min="0"
                    class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
            </div>
        </div>

        <div>
            <label for="stock_threshold" class="font-mono text-xs text-primary/70 block mb-1.5">Stock Threshold</label>
            <input type="number" id="stock_threshold" name="stock_threshold" value="{{ old('stock_threshold', $stok->stock_threshold ?? 0) }}" min="0"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="harga" class="font-mono text-xs text-primary/70 block mb-1.5">Harga</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga', $stok->harga ?? 0) }}" min="0"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Update
        </button>
    </form>
</div>
@endsection
