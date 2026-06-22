<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use App\Models\BarangToko;

class BarangController extends Controller
{
    public function index()
    {
        $tokoId = session('toko_id');
        $query = Barang::select('barangs.*', 'barang_toko.stok_gudang', 'barang_toko.stok_packing', 'barang_toko.total_stok', 'barang_toko.stock_threshold', 'barang_toko.harga')
            ->join('barang_toko', function ($join) use ($tokoId) {
                $join->on('barangs.id', '=', 'barang_toko.barang_id')
                    ->where('barang_toko.toko_id', $tokoId);
            });

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $sort = request('sort');
        if ($sort === 'nama') {
            $query->orderBy('nama_barang');
        } elseif ($sort === 'kode_asc') {
            $query->orderByRaw('CAST(SUBSTRING(kode_barang, LOCATE("-", kode_barang) + 1) AS UNSIGNED) ASC');
        } elseif ($sort === 'kode_desc') {
            $query->orderByRaw('CAST(SUBSTRING(kode_barang, LOCATE("-", kode_barang) + 1) AS UNSIGNED) DESC');
        } else {
            $query->latest('barangs.created_at');
        }

        $barangs = $query->paginate(10);

        return view('barangs.index', compact('barangs'));
    }

    public function create()
    {
        return view('barangs.create');
    }

    public function store(BarangRequest $request)
    {
        $data = $request->validated();
        $tokoId = session('toko_id');

        $barang = Barang::firstOrCreate(
            ['kode_barang' => $data['kode_barang']],
            ['nama_barang' => $data['nama_barang']],
        );

        if (BarangToko::where('barang_id', $barang->id)->where('toko_id', $tokoId)->exists()) {
            return back()->withErrors(['kode_barang' => 'Barang sudah terdaftar di toko ini.'])->withInput();
        }

        $stokGudang = (int) ($data['stok_gudang'] ?? 0);
        $stokPacking = (int) ($data['stok_packing'] ?? 0);

        BarangToko::create([
            'barang_id' => $barang->id,
            'toko_id' => $tokoId,
            'stok_gudang' => $stokGudang,
            'stok_packing' => $stokPacking,
            'total_stok' => $stokGudang + $stokPacking,
            'stock_threshold' => (int) ($data['stock_threshold'] ?? 0),
            'harga' => (int) ($data['harga'] ?? 0),
        ]);

        $msg = $barang->wasRecentlyCreated
            ? 'Barang berhasil ditambahkan.'
            : 'Barang berhasil ditambahkan ke toko ini.';

        return redirect()->route('barangs.index')->with('success', $msg);
    }

    public function edit(Barang $barang)
    {
        $barang->load(['tokos' => function ($q) {
            $q->where('toko_id', session('toko_id'));
        }]);
        $stok = $barang->tokos->first()?->pivot;

        return view('barangs.edit', compact('barang', 'stok'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $barang->update($request->safe()->only(['kode_barang', 'nama_barang']));

        $pivot = BarangToko::where('barang_id', $barang->id)
            ->where('toko_id', session('toko_id'))
            ->first();

        if ($pivot) {
            $stokGudang = (int) ($request->input('stok_gudang', $pivot->stok_gudang));
            $stokPacking = (int) ($request->input('stok_packing', $pivot->stok_packing));
            $pivot->update([
                'stok_gudang' => $stokGudang,
                'stok_packing' => $stokPacking,
                'total_stok' => $stokGudang + $stokPacking,
                'stock_threshold' => (int) ($request->input('stock_threshold', $pivot->stock_threshold)),
                'harga' => (int) ($request->input('harga', $pivot->harga)),
            ]);
        }

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus.');
    }
}
