<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\BarangToko;
use App\Models\Transaksi;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    public function create()
    {
        $tokoId = session('toko_id');
        $barangToko = BarangToko::with('barang')->where('toko_id', $tokoId)->get();
        $barangsJson = $barangToko->filter(fn ($bt) => $bt->barang)->map(fn ($bt) => [
            'id' => $bt->barang->id,
            'kode' => $bt->barang->kode_barang,
            'nama' => $bt->barang->nama_barang,
            'stok_gudang' => $bt->stok_gudang,
            'stok_packing' => $bt->stok_packing,
            'total_stok' => $bt->total_stok,
        ])->values();

        return view('transaksi.create', compact('barangsJson'));
    }

    public function store(TransaksiRequest $request)
    {
        $data = $request->validated();
        $tokoId = session('toko_id');

        DB::transaction(function () use ($data, $tokoId) {
            $transaksi = Transaksi::create([
                'toko_id' => $tokoId,
                'user_id' => auth()->id(),
                'nama_pelanggan' => $data['nama_pelanggan'],
            ]);

            foreach ($data['items'] as $item) {
                $pivot = BarangToko::where('barang_id', $item['barang_id'])
                    ->where('toko_id', $tokoId)
                    ->firstOrFail();

                if ($item['quantity'] > $pivot->total_stok) {
                    throw ValidationException::withMessages([
                        'items.'.array_search($item, $data['items']).'.quantity' => __('Jumlah melebihi stok yang tersedia untuk :barang. Total stok saat ini: :stok', [
                            'barang' => $pivot->barang->nama_barang,
                            'stok' => $pivot->total_stok,
                        ]),
                    ]);
                }

                if ($item['quantity'] > $pivot->stok_packing) {
                    throw ValidationException::withMessages([
                        'items.'.array_search($item, $data['items']).'.quantity' => __('Stok packing tidak mencukupi untuk :barang. Stok packing saat ini: :stok. Transfer stok dari Gudang ke Packing terlebih dahulu.', [
                            'barang' => $pivot->barang->nama_barang,
                            'stok' => $pivot->stok_packing,
                        ]),
                    ]);
                }

                $stokAwalSnapshot = $pivot->total_stok;

                $pivot->decrement('stok_packing', $item['quantity']);
                $pivot->decrement('total_stok', $item['quantity']);

                TransaksiKeluar::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'stok_awal_snapshot' => $stokAwalSnapshot,
                    'harga_snapshot' => $pivot->harga,
                ]);
            }
        });

        return redirect()->route('transaksi.create')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function history(Request $request)
    {
        $tokoId = session('toko_id');
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search', '');

        $start = Carbon::parse($date, 'Asia/Jakarta')->startOfDay()->utc();
        $end = Carbon::parse($date, 'Asia/Jakarta')->endOfDay()->utc();

        $transaksis = Transaksi::with('details.barang', 'user', 'toko')
            ->where('toko_id', $tokoId)
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->where('nama_pelanggan', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate(10);

        $grouped = Transaksi::selectRaw('DATE(created_at + INTERVAL 7 HOUR) as tanggal, COUNT(*) as total_transaksi')
            ->where('toko_id', $tokoId)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        $totalQuantity = TransaksiKeluar::whereHas('transaksi', fn ($q) => $q->where('toko_id', $tokoId)
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->where('nama_pelanggan', 'like', '%'.$search.'%'))
        )->sum('quantity');

        return view('transaksi.history', compact('transaksis', 'grouped', 'date', 'search', 'totalQuantity'));
    }

    public function cetak(Transaksi $transaksi)
    {
        $transaksi->load('details.barang', 'user', 'toko');

        return view('transaksi.cetak', compact('transaksi'));
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
