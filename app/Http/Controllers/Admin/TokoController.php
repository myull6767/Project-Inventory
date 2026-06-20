<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TokoRequest;
use App\Models\Toko;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::latest()->paginate(10);

        return view('admin.tokos.index', compact('tokos'));
    }

    public function create()
    {
        return view('admin.tokos.create');
    }

    public function store(TokoRequest $request)
    {
        Toko::create($request->validated());

        return redirect()->route('admin.tokos.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    public function edit(Toko $toko)
    {
        return view('admin.tokos.edit', compact('toko'));
    }

    public function update(TokoRequest $request, Toko $toko)
    {
        $toko->update($request->validated());

        return redirect()->route('admin.tokos.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Toko $toko)
    {
        $toko->delete();

        return redirect()->route('admin.tokos.index')->with('success', 'Toko berhasil dihapus.');
    }
}
