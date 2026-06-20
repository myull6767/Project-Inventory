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
            <label for="barang_id" class="font-mono text-xs text-primary/70 block mb-1.5">Barang</label>
            <select id="barang_id" name="barang_id" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">— Pilih Barang —</option>
                @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}"
                    data-stok="{{ $barang->total_stok }}"
                    data-packing="{{ $barang->stok_packing }}"
                    data-gudang="{{ $barang->stok_gudang }}"
                    {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                    {{ $barang->kode_barang }} — {{ $barang->nama_barang }}
                </option>
                @endforeach
            </select>
            <div id="stok-info" class="font-mono text-xs text-primary/50 mt-1"></div>
        </div>

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
