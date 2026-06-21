<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Transaksi — LJN Inventory</title>
    @vite(['resources/css/app.css'])
    <style nonce="{{ $cspNonce ?? '' }}">
        @@media print {
            body { background: white; }
            .no-print { display: none !important; }
            @@page { margin: 1.5cm; }
        }
    </style>
</head>
<body class="bg-surface text-primary font-sans antialiased">
    <div class="max-w-lg mx-auto p-6">
        <div class="text-center mb-6">
            <h1 class="font-mono text-xl font-bold text-secondary">LJN Inventory</h1>
            <p class="font-mono text-xs text-primary/50">Nota Barang Keluar</p>
        </div>

        <div class="border-t border-b border-primary/10 py-3 mb-5 font-mono text-xs text-primary/70 space-y-1">
            <div class="flex justify-between">
                <span>No. Transaksi: TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span>{{ $transaksi->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</span>
            </div>
            <div class="flex justify-between">
                <span>Kode Toko: {{ $transaksi->kode_toko_inputed }}</span>
                <span>Petugas: {{ $transaksi->user->name ?? '-' }}</span>
            </div>
        </div>

        <table class="w-full text-xs font-mono mb-5">
            <thead>
                <tr class="border-b border-primary/20">
                    <th class="text-left py-2 text-primary/50 font-medium">Barang</th>
                    <th class="text-right py-2 text-primary/50 font-medium">Jumlah</th>
                    <th class="text-right py-2 text-primary/50 font-medium">Stok Awal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $detail)
                <tr class="border-b border-primary/5">
                    <td class="py-2 text-primary">
                        <div>{{ $detail->barang->nama_barang ?? '-' }}</div>
                        <div class="text-[10px] text-primary/40">{{ $detail->barang->kode_barang ?? '' }}</div>
                    </td>
                    <td class="py-2 text-right text-primary align-top">{{ $detail->quantity }}</td>
                    <td class="py-2 text-right text-primary/50 align-top">{{ $detail->stok_awal_snapshot }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="font-mono text-xs text-right pt-2 border-t border-primary/10">
            Total item: <span class="text-secondary font-medium">{{ $transaksi->details->sum('quantity') }}</span>
        </div>

        <div class="text-center mt-8 text-[10px] font-mono text-primary/30 no-print">
            <button onclick="window.print()" class="px-4 py-2 bg-secondary text-on-primary rounded-md hover:opacity-90 transition-opacity">
                Cetak / Simpan PDF
            </button>
            <span class="block mt-2">atau tekan Ctrl+P</span>
        </div>
    </div>

    <script nonce="{{ $cspNonce ?? '' }}">setTimeout(function () { window.print(); }, 500);</script>
</body>
</html>
