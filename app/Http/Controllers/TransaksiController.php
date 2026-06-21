<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\Barang;
use App\Models\Toko;
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
        $barangs = Barang::all();
        $tokos = Toko::all();
        $barangsJson = $barangs->map(function ($b) {
            return [
                'id' => $b->id,
                'kode' => $b->kode_barang,
                'nama' => $b->nama_barang,
                'stok_gudang' => $b->stok_gudang,
                'stok_packing' => $b->stok_packing,
                'total_stok' => $b->total_stok,
            ];
        });

        return view('transaksi.create', compact('barangsJson', 'tokos'));
    }

    public function store(TransaksiRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $transaksi = Transaksi::create([
                'kode_toko_inputed' => $data['kode_toko_inputed'],
                'user_id' => auth()->id(),
            ]);

            foreach ($data['items'] as $item) {
                $barang = Barang::findOrFail($item['barang_id']);

                if ($item['quantity'] > $barang->total_stok) {
                    throw ValidationException::withMessages([
                        'items.'.array_search($item, $data['items']).'.quantity' => __('Jumlah melebihi stok yang tersedia untuk :barang. Total stok saat ini: :stok', [
                            'barang' => $barang->nama_barang,
                            'stok' => $barang->total_stok,
                        ]),
                    ]);
                }

                if ($item['quantity'] > $barang->stok_packing) {
                    throw ValidationException::withMessages([
                        'items.'.array_search($item, $data['items']).'.quantity' => __('Stok packing tidak mencukupi untuk :barang. Stok packing saat ini: :stok. Transfer stok dari Gudang ke Packing terlebih dahulu.', [
                            'barang' => $barang->nama_barang,
                            'stok' => $barang->stok_packing,
                        ]),
                    ]);
                }

                $stokAwalSnapshot = $barang->total_stok;

                $barang->decrement('stok_packing', $item['quantity']);
                $barang->decrement('total_stok', $item['quantity']);

                TransaksiKeluar::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barang->id,
                    'quantity' => $item['quantity'],
                    'stok_awal_snapshot' => $stokAwalSnapshot,
                ]);
            }
        });

        return redirect()->route('transaksi.create')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function history(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search', '');

        $start = Carbon::parse($date, 'Asia/Jakarta')->startOfDay()->utc();
        $end = Carbon::parse($date, 'Asia/Jakarta')->endOfDay()->utc();

        $transaksis = Transaksi::with('details.barang', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->where('kode_toko_inputed', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate(10);

        $grouped = Transaksi::selectRaw('DATE(created_at + INTERVAL 7 HOUR) as tanggal, COUNT(*) as total_transaksi')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        $totalQuantity = TransaksiKeluar::whereHas('transaksi', fn ($q) => $q->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->where('kode_toko_inputed', 'like', '%'.$search.'%'))
        )->sum('quantity');

        return view('transaksi.history', compact('transaksis', 'grouped', 'date', 'search', 'totalQuantity'));
    }

    public function cetak(Transaksi $transaksi)
    {
        $transaksi->load('details.barang', 'user');

        return view('transaksi.cetak', compact('transaksi'));
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
