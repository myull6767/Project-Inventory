<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        $query = Barang::query();

        if ($search = request('search')) {
            $query->where('nama_barang', 'like', "%{$search}%")->orWhere('kode_barang', 'like', "%{$search}%");
        }

        $sort = request('sort');
        if ($sort === 'nama') {
            $query->orderBy('nama_barang');
        } elseif ($sort === 'kode_asc') {
            $query->orderByRaw('CAST(SUBSTRING(kode_barang, LOCATE("-", kode_barang) + 1) AS UNSIGNED) ASC');
        } elseif ($sort === 'kode_desc') {
            $query->orderByRaw('CAST(SUBSTRING(kode_barang, LOCATE("-", kode_barang) + 1) AS UNSIGNED) DESC');
        } else {
            $query->latest();
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
        $data['stok_gudang'] ??= 0;
        $data['stok_packing'] ??= 0;
        $data['total_stok'] = $data['stok_gudang'] + $data['stok_packing'];
        $data['stock_threshold'] ??= 0;

        Barang::create($data);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barangs.edit', compact('barang'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $data = $request->validated();
        $data['total_stok'] = ($data['stok_gudang'] ?? $barang->stok_gudang) + ($data['stok_packing'] ?? $barang->stok_packing);

        $barang->update($data);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus.');
    }
}
