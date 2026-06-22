@extends('layouts.app')

@section('title', 'Riwayat Transaksi — LJN Inventory')

@section('content')
<h1 class="font-mono text-lg text-secondary mb-6">Riwayat Transaksi</h1>

<form method="GET" action="{{ route('transaksi.history') }}" class="mb-6 flex flex-wrap gap-3">
    <input type="date" name="date" value="{{ $date }}" data-auto-submit
        class="px-3 py-2 border border-primary/20 rounded-md text-sm font-mono focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pelanggan..." autocomplete="off"
        class="px-3 py-2 border border-primary/20 rounded-md text-sm font-mono focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary flex-1 min-w-[180px]">
    <button type="submit"
        class="px-4 py-2 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">Cari</button>
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
                    <span class="font-mono text-xs text-primary/60">{{ $g->total_transaksi }} transaksi</span>
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
            <div class="space-y-4">
                @foreach ($transaksis as $transaksi)
                <div class="border border-primary/5 rounded overflow-hidden">
                    <div class="flex justify-between items-center px-3 py-2 bg-neutral/50">
                        <div class="font-mono text-[10px] text-primary/50">
                            <span class="text-secondary/70">{{ $transaksi->created_at->format('H:i') }}</span>
                            <span class="ml-2">Pelanggan: {{ $transaksi->nama_pelanggan }}</span>
                            @if ($transaksi->user)
                            <span class="ml-2 text-secondary/50">oleh {{ $transaksi->user->name }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('transaksi.cetak', $transaksi) }}" target="_blank"
                                class="py-1 px-2 font-mono text-xs rounded border border-secondary/20 text-secondary hover:bg-secondary/5 transition-colors">
                                Cetak
                            </a>
                            <form method="POST" action="{{ route('transaksi.destroy', $transaksi) }}" data-confirm="Hapus seluruh transaksi ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50 transition-colors">Hapus</button>
                            </form>
                        </div>
                    </div>
                    <div class="divide-y divide-primary/5">
                        @foreach ($transaksi->details as $detail)
                        <div class="flex justify-between items-center px-3 py-2">
                            <div class="flex-1">
                                <span class="font-mono text-xs text-primary">{{ $detail->barang->nama_barang ?? '-' }}</span>
                                <span class="font-mono text-[10px] text-primary/40 ml-2">{{ $detail->barang->kode_barang ?? '' }}</span>
                                <div class="font-mono text-[10px] text-primary/40 mt-0.5">
                                    Stok awal: {{ $detail->stok_awal_snapshot }}
                                </div>
                            </div>
                            <span class="font-mono text-xs text-tertiary">{{ $detail->quantity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-primary/10 flex justify-between font-mono text-xs text-primary">
                <span>Total transaksi: {{ $transaksis->total() }}</span>
                <span>Total item: {{ $totalQuantity }}</span>
            </div>

            <div class="mt-4">
                {{ $transaksis->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
