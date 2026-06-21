@extends('layouts.app')

@section('title', 'Barang Masuk — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Barang Masuk — Dari Supplier</h1>
    <p class="font-mono text-xs text-primary/50 mb-6">Barang masuk dari supplier ke Gudang</p>

    <form method="POST" action="{{ route('barang-masuk.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf

        <div>
            <label for="barang_search" class="font-mono text-xs text-primary/70 block mb-1.5">Barang</label>
            <input type="text" id="barang_search" autocomplete="off" required
                placeholder="Ketik kode atau nama barang..."
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
            <input type="hidden" name="barang_id" id="barang_id" value="{{ old('barang_id') }}">
            <div id="barang-dropdown" class="hidden mt-1 border border-primary/20 rounded-md bg-surface shadow-lg max-h-48 overflow-y-auto"></div>
            <div class="stok-info font-mono text-xs text-primary/50 mt-1"></div>
        </div>

        <div>
            <label for="supplier_id" class="font-mono text-xs text-primary/70 block mb-1.5">Supplier</label>
            <select id="supplier_id" name="supplier_id" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">— Pilih Supplier —</option>
                @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->nama_supplier }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="quantity" class="font-mono text-xs text-primary/70 block mb-1.5">Jumlah</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required min="1"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Simpan
        </button>
    </form>
</div>

<script id="barangs-data" type="application/json" nonce="{{ $cspNonce ?? '' }}">@json($barangsJson)</script>
@vite(['resources/js/barang-masuk.js'])
@endsection
