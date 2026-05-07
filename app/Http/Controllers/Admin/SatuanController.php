<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SatuanController extends Controller
{
    public function index()
    {
        $satuanList = Satuan::withCount('masterProducts')
            ->orderBy('nama')
            ->paginate(20);

        return view('admin.satuan.index', compact('satuanList'));
    }

    public function create()
    {
        return view('admin.satuan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'singkatan'  => 'required|string|max:20|unique:satuan,singkatan',
        ]);

        $data['singkatan'] = strtolower(trim($data['singkatan']));

        Satuan::create($data);

        return redirect()->route('admin.satuan.index')
            ->with('success', "Satuan \"{$data['nama']}\" berhasil ditambahkan.");
    }

    public function edit(Satuan $satuan)
    {
        return view('admin.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'singkatan' => ['required', 'string', 'max:20', Rule::unique('satuan', 'singkatan')->ignore($satuan->id)],
        ]);

        $data['singkatan'] = strtolower(trim($data['singkatan']));

        $satuan->update($data);

        return redirect()->route('admin.satuan.index')
            ->with('success', "Satuan \"{$satuan->nama}\" berhasil diperbarui.");
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->masterProducts()->exists()) {
            return back()->with('error', "Satuan \"{$satuan->nama}\" tidak bisa dihapus karena masih digunakan oleh master produk.");
        }

        $satuan->delete();

        return redirect()->route('admin.satuan.index')
            ->with('success', "Satuan \"{$satuan->nama}\" berhasil dihapus.");
    }
}
