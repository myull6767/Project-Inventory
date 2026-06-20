@extends('layouts.app')

@section('title', 'Riwayat Transaksi — LJN Inventory')

@section('content')
<h1 class="font-mono text-lg text-secondary mb-6">Riwayat Transaksi</h1>

<form method="GET" action="{{ route('transaksi.history') }}" class="mb-6">
    <input type="date" name="date" value="{{ $date }}"
        class="px-3 py-2 border border-primary/20 rounded-md text-sm font-mono focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary"
        onchange="this.form.submit()">
</form>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-surface border border-secondary/10 rounded-lg p-4">
            <h2 class="font-mono text-xs text-secondary mb-3">Ringkasan Harian</h2>
            <div class="space-y-2">
                @forelse ($grouped as $g)
                <a href="{{ route('transaksi.history', ['date' => $g->tanggal]) }}"
                    class="flex justify-between items-center p-2 rounded {{ $date === $g->tanggal ? 'bg-secondary/10' : 'hover:bg-neutral' }}">
                    <span class="font-mono text-xs text-primary">{{ \Carbon\Carbon::parse($g->tanggal)->format('d M Y') }}</span>
                    <span class="font-mono text-xs text-primary/60">{{ $g->total_quantity }} item ({{ $g->total_transaksi }} tx)</span>
                </a>
                @empty
                <p class="font-mono text-xs text-primary/50">Belum ada transaksi.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-surface border border-secondary/10 rounded-lg p-4">
            <h2 class="font-mono text-xs text-secondary mb-3">
                Transaksi — {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </h2>

            @if ($transaksis->isEmpty())
            <p class="font-mono text-xs text-primary/50">Tidak ada transaksi pada tanggal ini.</p>
            @else
            <div class="space-y-3">
                @foreach ($transaksis as $t)
                <div class="flex justify-between items-center p-3 border border-primary/5 rounded">
                    <div class="flex-1">
                        <span class="font-mono text-xs text-primary">{{ $t->barang->nama_barang ?? '-' }}</span>
                        <span class="font-mono text-[10px] text-primary/40 ml-2">{{ $t->barang->kode_barang ?? '' }}</span>
                        <div class="font-mono text-[10px] text-primary/40 mt-0.5">
                            Stok awal: {{ $t->stok_awal_snapshot }} → Toko: {{ $t->kode_toko_inputed }}
                            <span class="text-primary/30 ml-1">{{ $t->created_at->format('H:i') }}</span>
                            @if ($t->user)
                            <span class="ml-2 text-secondary/50">oleh {{ $t->user->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-xs text-tertiary">{{ $t->quantity }}</span>
                        <form method="POST" action="{{ route('transaksi.destroy', $t->id) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-primary/10 flex justify-between font-mono text-xs text-primary">
                <span>Total transaksi: {{ $transaksis->count() }}</span>
                <span>Total item: {{ $transaksis->sum('quantity') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
