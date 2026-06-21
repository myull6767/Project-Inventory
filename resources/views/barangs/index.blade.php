@extends('layouts.app')

@section('title', 'Barang — LJN Inventory')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-mono text-lg text-secondary">Master Barang</h1>
    <a href="{{ route('barangs.create') }}" class="py-2 px-4 bg-tertiary text-on-primary font-mono text-xs rounded-md hover:opacity-90">Tambah Barang</a>
</div>

<div class="flex flex-wrap gap-3 mb-4 items-end">
    <form method="GET" action="{{ route('barangs.index') }}" class="flex gap-3 items-end flex-wrap">
        <div>
            <label for="search" class="block font-mono text-xs text-primary/60 mb-1">Cari</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" autocomplete="off"
                placeholder="Nama atau kode barang..."
                class="w-56 px-3 py-2 border border-primary/20 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>
        <div>
            <label for="sort" class="block font-mono text-xs text-primary/60 mb-1">Urut</label>
            <select id="sort" name="sort" data-auto-submit
                class="px-3 py-2 border border-primary/20 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">Terbaru</option>
                <option value="nama" {{ request('sort') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="kode_asc" {{ request('sort') === 'kode_asc' ? 'selected' : '' }}>Kode ▲</option>
                <option value="kode_desc" {{ request('sort') === 'kode_desc' ? 'selected' : '' }}>Kode ▼</option>
            </select>
        </div>
        @if(request('search') || request('sort'))
        <a href="{{ route('barangs.index') }}" class="font-mono text-xs text-primary/50 hover:text-secondary mt-5">Reset</a>
        @endif
    </form>
</div>

<div class="bg-surface border border-secondary/10 rounded-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-secondary/10 font-mono text-xs text-primary/60">
                <th class="text-left py-3 px-4">Kode</th>
                <th class="text-left py-3 px-4">Nama</th>
                <th class="text-right py-3 px-4">Gudang</th>
                <th class="text-right py-3 px-4">Packing</th>
                <th class="text-right py-3 px-4">Total</th>
                <th class="text-right py-3 px-4">Threshold</th>
                <th class="text-right py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangs as $barang)
            <tr class="border-b border-primary/5">
                <td class="py-3 px-4 font-mono text-xs">{{ $barang->kode_barang }}</td>
                <td class="py-3 px-4">{{ $barang->nama_barang }}</td>
                <td class="py-3 px-4 text-right font-mono text-xs">{{ $barang->stok_gudang }}</td>
                <td class="py-3 px-4 text-right font-mono text-xs">{{ $barang->stok_packing }}</td>
                <td class="py-3 px-4 text-right font-mono text-xs {{ $barang->total_stok <= $barang->stock_threshold ? 'text-tertiary' : '' }}">{{ $barang->total_stok }}</td>
                <td class="py-3 px-4 text-right font-mono text-xs">{{ $barang->stock_threshold }}</td>
                <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('barangs.edit', $barang) }}" class="py-1 px-2 font-mono text-xs rounded border border-secondary/20 text-secondary hover:bg-secondary/5">Edit</a>
                    <form method="POST" action="{{ route('barangs.destroy', $barang) }}" data-confirm="Hapus barang {{ $barang->nama_barang }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-6 text-center text-xs text-primary/50">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $barangs->withQueryString()->links() }}
</div>
@endsection
