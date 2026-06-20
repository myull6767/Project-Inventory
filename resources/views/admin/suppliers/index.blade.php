@extends('layouts.app')

@section('title', 'Manage Supplier — LJN Inventory')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-mono text-lg text-secondary">Manage Supplier</h1>
    <a href="{{ route('admin.suppliers.create') }}" class="py-2 px-4 bg-tertiary text-on-primary font-mono text-xs rounded-md hover:opacity-90">Tambah Supplier</a>
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
            @forelse ($suppliers as $supplier)
            <tr class="border-b border-primary/5">
                <td class="py-3 px-4 font-mono text-xs">{{ $supplier->kode_supplier }}</td>
                <td class="py-3 px-4">{{ $supplier->nama_supplier }}</td>
                <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="py-1 px-2 font-mono text-xs rounded border border-secondary/20 text-secondary hover:bg-secondary/5">Edit</a>
                    <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier {{ $supplier->nama_supplier }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="py-6 text-center text-xs text-primary/50">Belum ada supplier.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $suppliers->links() }}
</div>
@endsection
