<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangMasukRequest;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function create()
    {
        $barangs = Barang::all();
        $suppliers = Supplier::all();

        return view('barang-masuk.create', compact('barangs', 'suppliers'));
    }

    public function store(BarangMasukRequest $request)
    {
        $data = $request->validated();

        $barang = Barang::findOrFail($data['barang_id']);
        $barang->increment('stok_gudang', $data['quantity']);
        $barang->increment('total_stok', $data['quantity']);

        $data['type'] = 'supplier';
        BarangMasuk::create($data);

        return redirect()->route('barang-masuk.create')->with('success', 'Barang masuk dari supplier berhasil dicatat.');
    }

    public function createPacking()
    {
        $barangs = Barang::where('stok_gudang', '>', 0)->get();

        return view('barang-masuk.packing', compact('barangs'));
    }

    public function storePacking(BarangMasukRequest $request)
    {
        $data = $request->validated();

        $barang = Barang::findOrFail($data['barang_id']);

        if ($data['quantity'] > $barang->stok_gudang) {
            return back()->withErrors(['quantity' => 'Stok gudang tidak mencukupi. Stok gudang saat ini: '.$barang->stok_gudang]);
        }

        $barang->decrement('stok_gudang', $data['quantity']);
        $barang->increment('stok_packing', $data['quantity']);

        BarangMasuk::create([
            'barang_id' => $barang->id,
            'supplier_id' => null,
            'quantity' => $data['quantity'],
            'type' => 'packing',
        ]);

        return redirect()->route('barang-masuk.packing')->with('success', 'Transfer ke packing berhasil.');
    }

    public function history(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search', '');

        $start = Carbon::parse($date, 'Asia/Jakarta')->startOfDay()->utc();
        $end = Carbon::parse($date, 'Asia/Jakarta')->endOfDay()->utc();

        $barangMasuks = BarangMasuk::with('barang', 'supplier')
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->whereHas('barang', fn ($q) => $q->where('nama_barang', 'like', '%'.$search.'%')))
            ->latest()
            ->get();

        $grouped = BarangMasuk::selectRaw('DATE(created_at + INTERVAL 7 HOUR) as tanggal, COUNT(*) as total_transaksi, SUM(quantity) as total_quantity')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        return view('barang-masuk.history', compact('barangMasuks', 'grouped', 'date', 'search'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->delete();

        return back()->with('success', 'Barang masuk berhasil dihapus.');
    }
}
