<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangMasukRequest;
use App\Models\BarangMasuk;
use App\Models\BarangToko;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function create()
    {
        $tokoId = session('toko_id');
        $barangToko = BarangToko::with('barang')->where('toko_id', $tokoId)->get();
        $suppliers = Supplier::all();
        $barangsJson = $barangToko->filter(fn ($bt) => $bt->barang)->map(fn ($bt) => [
            'id' => $bt->barang->id,
            'kode' => $bt->barang->kode_barang,
            'nama' => $bt->barang->nama_barang,
            'stok_gudang' => $bt->stok_gudang,
            'stok_packing' => $bt->stok_packing,
            'total_stok' => $bt->total_stok,
        ])->values();

        return view('barang-masuk.create', compact('barangsJson', 'suppliers'));
    }

    public function store(BarangMasukRequest $request)
    {
        $data = $request->validated();
        $tokoId = session('toko_id');

        $pivot = BarangToko::where('barang_id', $data['barang_id'])
            ->where('toko_id', $tokoId)
            ->firstOrFail();

        $pivot->increment('stok_gudang', $data['quantity']);
        $pivot->increment('total_stok', $data['quantity']);

        $data['type'] = 'supplier';
        $data['toko_id'] = $tokoId;
        BarangMasuk::create($data);

        return redirect()->route('barang-masuk.create')->with('success', 'Barang masuk dari supplier berhasil dicatat.');
    }

    public function createPacking()
    {
        $tokoId = session('toko_id');
        $barangToko = BarangToko::with('barang')
            ->where('toko_id', $tokoId)
            ->where('stok_gudang', '>', 0)
            ->get();
        $barangsJson = $barangToko->filter(fn ($bt) => $bt->barang)->map(fn ($bt) => [
            'id' => $bt->barang->id,
            'kode' => $bt->barang->kode_barang,
            'nama' => $bt->barang->nama_barang,
            'stok_gudang' => $bt->stok_gudang,
            'stok_packing' => $bt->stok_packing,
            'total_stok' => $bt->total_stok,
        ])->values();

        return view('barang-masuk.packing', compact('barangsJson'));
    }

    public function storePacking(BarangMasukRequest $request)
    {
        $data = $request->validated();
        $tokoId = session('toko_id');

        $pivot = BarangToko::where('barang_id', $data['barang_id'])
            ->where('toko_id', $tokoId)
            ->firstOrFail();

        if ($data['quantity'] > $pivot->stok_gudang) {
            return back()->withErrors(['quantity' => 'Stok gudang tidak mencukupi. Stok gudang saat ini: '.$pivot->stok_gudang]);
        }

        $pivot->decrement('stok_gudang', $data['quantity']);
        $pivot->increment('stok_packing', $data['quantity']);

        BarangMasuk::create([
            'barang_id' => $pivot->barang_id,
            'supplier_id' => null,
            'quantity' => $data['quantity'],
            'type' => 'packing',
            'toko_id' => $tokoId,
        ]);

        return redirect()->route('barang-masuk.packing')->with('success', 'Transfer ke packing berhasil.');
    }

    public function history(Request $request)
    {
        $tokoId = session('toko_id');
        $date = $request->input('date', now()->format('Y-m-d'));
        $search = $request->input('search', '');

        $start = Carbon::parse($date, 'Asia/Jakarta')->startOfDay()->utc();
        $end = Carbon::parse($date, 'Asia/Jakarta')->endOfDay()->utc();

        $barangMasuks = BarangMasuk::with('barang', 'supplier')
            ->where('toko_id', $tokoId)
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->whereHas('barang', fn ($q) => $q->where('nama_barang', 'like', '%'.$search.'%')))
            ->latest()
            ->paginate(10);

        $grouped = BarangMasuk::selectRaw('DATE(created_at + INTERVAL 7 HOUR) as tanggal, COUNT(*) as total_transaksi, SUM(quantity) as total_quantity')
            ->where('toko_id', $tokoId)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        $totalQuantity = BarangMasuk::where('toko_id', $tokoId)
            ->whereBetween('created_at', [$start, $end])
            ->when($search, fn ($q) => $q->whereHas('barang', fn ($q) => $q->where('nama_barang', 'like', '%'.$search.'%')))
            ->sum('quantity');

        return view('barang-masuk.history', compact('barangMasuks', 'grouped', 'date', 'search', 'totalQuantity'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->delete();

        return back()->with('success', 'Barang masuk berhasil dihapus.');
    }
}
