@extends('layouts.app')

@section('title', 'Tambah Toko — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Tambah Toko</h1>

    <form method="POST" action="{{ route('admin.tokos.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf

        <div>
            <label for="kode_toko" class="font-mono text-xs text-primary/70 block mb-1.5">Kode Toko</label>
            <input type="text" id="kode_toko" name="kode_toko" value="{{ old('kode_toko') }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="nama_toko" class="font-mono text-xs text-primary/70 block mb-1.5">Nama Toko</label>
            <input type="text" id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Simpan
        </button>
    </form>
</div>
@endsection
