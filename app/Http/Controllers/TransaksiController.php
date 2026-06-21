<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Models\Barang;
use App\Models\Toko;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        return view('transaksi.create', compact('barangs', 'tokos', 'barangsJson'));
    }

    public function store(TransaksiRequest $request)
    {
        $data = $request->validated();

        $barang = Barang::findOrFail($data['barang_id']);

        if ($data['quantity'] > $barang->total_stok) {
            throw ValidationException::withMessages([
                'quantity' => __('Jumlah melebihi stok yang tersedia. Total stok saat ini: :stok', ['stok' => $barang->total_stok]),
            ]);
        }

        if ($data['quantity'] > $barang->stok_packing) {
            throw ValidationException::withMessages([
                'quantity' => __('Stok packing tidak mencukupi. Stok packing saat ini: :stok. Transfer stok dari Gudang ke Packing terlebih dahulu.', ['stok' => $barang->stok_packing]),
            ]);
        }

        $stokAwalSnapshot = $barang->total_stok;

        $barang->decrement('stok_packing', $data['quantity']);
        $barang->decrement('total_stok', $data['quantity']);

        TransaksiKeluar::create([
            'barang_id' => $barang->id,
            'user_id' => auth()->id(),
            'kode_toko_inputed' => $data['kode_toko_inputed'],
            'quantity' => $data['quantity'],
            'stok_awal_snapshot' => $stokAwalSnapshot,
        ]);

        return redirect()->route('transaksi.create')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function history(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $start = Carbon::parse($date, 'Asia/Jakarta')->startOfDay()->utc();
        $end = Carbon::parse($date, 'Asia/Jakarta')->endOfDay()->utc();

        $transaksis = TransaksiKeluar::with('barang', 'user')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $grouped = TransaksiKeluar::selectRaw('DATE(created_at + INTERVAL 7 HOUR) as tanggal, COUNT(*) as total_transaksi, SUM(quantity) as total_quantity')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        return view('transaksi.history', compact('transaksis', 'grouped', 'date'));
    }

    public function destroy($id)
    {
        TransaksiKeluar::findOrFail($id)->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
