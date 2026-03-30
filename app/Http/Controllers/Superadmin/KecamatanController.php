<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kecamatan;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::latest()->get();
        return view('superadmin.kecamatans.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('superadmin.kecamatans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'kode_kecamatan' => 'required|string|max:50|unique:kecamatans,kode_kecamatan',
            'alamat' => 'nullable|string',
        ]);

        Kecamatan::create($validated);

        return redirect()->route('superadmin.kecamatans.index')
            ->with('success', 'Data kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('superadmin.kecamatans.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'kode_kecamatan' => 'required|string|max:50|unique:kecamatans,kode_kecamatan,' . $kecamatan->id,
            'alamat' => 'nullable|string',
        ]);

        $kecamatan->update($validated);

        return redirect()->route('superadmin.kecamatans.index')
            ->with('success', 'Data kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();

        return redirect()->route('superadmin.kecamatans.index')
            ->with('success', 'Data kecamatan berhasil dihapus.');
    }
}
