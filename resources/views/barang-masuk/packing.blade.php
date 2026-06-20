@extends('layouts.app')

@section('title', 'Barang Masuk ke Packing — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Barang Masuk ke Packing</h1>
    <p class="font-mono text-xs text-primary/50 mb-6">Transfer stok dari Gudang ke Packing</p>

    <form method="POST" action="{{ route('barang-masuk.packing.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf
        <input type="hidden" name="type" value="packing">

        <div>
            <label for="barang_id" class="font-mono text-xs text-primary/70 block mb-1.5">Barang</label>
            <select id="barang_id" name="barang_id" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">— Pilih Barang —</option>
                @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}" data-gudang="{{ $barang->stok_gudang }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                    {{ $barang->kode_barang }} — {{ $barang->nama_barang }} (Gudang: {{ $barang->stok_gudang }})
                </option>
                @endforeach
            </select>
            <p id="stok-gudang-info" class="font-mono text-xs text-primary/50 mt-1"></p>
        </div>

        <div>
            <label for="quantity" class="font-mono text-xs text-primary/70 block mb-1.5">Jumlah</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required min="1" autocomplete="off"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Transfer
        </button>
    </form>
</div>

@vite(['resources/js/barang-masuk.js'])
@endsection
