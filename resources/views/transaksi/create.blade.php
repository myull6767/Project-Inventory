@extends('layouts.app')

@section('title', 'Transaksi — LJN Inventory')

@section('content')
<div class="max-w-2xl">
    <h1 class="font-mono text-lg text-secondary mb-6">Transaksi Barang Keluar</h1>

    @if (session('warning'))
    <div class="mb-6 p-4 bg-tertiary/10 border border-tertiary/30 rounded-md text-sm text-primary">
        {{ session('warning') }}
    </div>
    @endif

    <div class="mb-6 p-4 bg-secondary/5 border border-secondary/20 rounded-md font-mono text-xs text-primary/70">
        Toko: <span class="text-secondary font-medium">{{ $currentToko->nama_toko ?? '' }}</span>
    </div>

    <form method="POST" action="{{ route('transaksi.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf

        <div>
            <label for="nama_pelanggan" class="font-mono text-xs text-primary/70 block mb-1.5">Nama Pelanggan</label>
            <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required autocomplete="off"
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label class="font-mono text-xs text-primary/70 block mb-2">Daftar Barang</label>

            <div id="items-container" class="space-y-3">
            </div>

            <button type="button" id="add-row"
                class="mt-3 py-2 px-4 border border-secondary/30 text-secondary font-mono text-xs rounded-md hover:bg-secondary/5 transition-colors">
                + Tambah Barang
            </button>
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Simpan Transaksi
        </button>
    </form>
</div>

<template id="item-row-template">
    <div class="item-row border border-primary/10 rounded-md p-3">
        <div class="flex gap-3 items-start">
            <div class="flex-1 min-w-0">
                <input type="text" class="barang-search w-full px-3 py-2 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary" autocomplete="off"
                    placeholder="Ketik kode atau nama barang...">
                <input type="hidden" class="barang-id" name="items[__INDEX__][barang_id]">
                <div class="barang-dropdown hidden mt-1 border border-primary/20 rounded-md bg-surface shadow-lg max-h-48 overflow-y-auto"></div>
                <div class="stok-info font-mono text-xs text-primary/50 mt-1"></div>
            </div>
            <div class="w-24 flex-shrink-0">
                <input type="number" class="barang-qty w-full px-3 py-2 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary" name="items[__INDEX__][quantity]" required min="1" placeholder="Qty">
            </div>
            <button type="button" class="remove-row flex-shrink-0 mt-2 p-1.5 text-red-400 hover:text-red-600 transition-colors rounded hover:bg-red-50" title="Hapus barang">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>
</template>

<script id="barangs-data" type="application/json">@json($barangsJson)</script>
@vite(['resources/js/transaksi.js'])
@endsection
