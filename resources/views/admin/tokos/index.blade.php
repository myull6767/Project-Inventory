@extends('layouts.app')

@section('title', 'Manage Toko — LJN Inventory')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-mono text-lg text-secondary">Manage Toko</h1>
    <a href="{{ route('admin.tokos.create') }}" class="py-2 px-4 bg-tertiary text-on-primary font-mono text-xs rounded-md hover:opacity-90">Tambah Toko</a>
</div>

<div class="bg-surface border border-secondary/10 rounded-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-secondary/10 font-mono text-xs text-primary/60">
                <th class="text-left py-3 px-4">Kode</th>
                <th class="text-left py-3 px-4">Nama</th>
                <th class="text-right py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tokos as $toko)
            <tr class="border-b border-primary/5">
                <td class="py-3 px-4 font-mono text-xs">{{ $toko->kode_toko }}</td>
                <td class="py-3 px-4">{{ $toko->nama_toko }}</td>
                <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('admin.tokos.edit', $toko) }}" class="py-1 px-2 font-mono text-xs rounded border border-secondary/20 text-secondary hover:bg-secondary/5">Edit</a>
                    <form method="POST" action="{{ route('admin.tokos.destroy', $toko) }}" data-confirm="Hapus toko {{ $toko->nama_toko }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-6 text-center text-xs text-primary/50">Belum ada toko.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $tokos->links() }}
</div>
@endsection
