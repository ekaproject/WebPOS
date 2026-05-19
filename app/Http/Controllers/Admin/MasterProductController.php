<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MasterProduct;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MasterProductController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterProduct::with(['category', 'satuan'])->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $masterProducts = $query->paginate(20)->withQueryString();
        $categories = Category::query()->visibleForMenu()->orderBy('name')->get();

        return view('admin.master-products.index', compact('masterProducts', 'categories'));
    }

    public function create()
    {
        $categories = Category::query()->visibleForMenu()->where('is_active', true)->orderBy('name')->get();
        $satuanList  = Satuan::orderBy('nama')->get();

        return view('admin.master-products.create', compact('categories', 'satuanList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'barcode'     => 'nullable|string|max:100',
            'ukuran'      => 'nullable|string|max:100',
            'unit'        => 'required|string|max:50',
            'price'       => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        // Cek duplikat: nama + ukuran + unit harus unik
        $duplicate = MasterProduct::where('name', $data['name'])
            ->where('ukuran', $data['ukuran'] ?? null)
            ->where('unit', $data['unit'])
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->withErrors(['name' => 'Produk dengan nama, ukuran, dan satuan yang sama sudah terdaftar di master produk.']);
        }

        DB::transaction(function () use ($request, $data) {
            $data['is_active'] = $request->boolean('is_active', true);
            $data['price'] = $request->input('price');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
                $file->move(public_path('storage/master-products'), $filename);
                $data['image'] = 'master-products/' . $filename;
            }

            // barcode diisi otomatis oleh model event setelah record disimpan.
            MasterProduct::create($data);
        });

        return redirect()->route('admin.master-products.index')
            ->with('success', 'Master produk berhasil ditambahkan.');
    }

    public function edit(MasterProduct $masterProduct)
    {
        $categories = Category::query()->visibleForMenu()->where('is_active', true)->orderBy('name')->get();
        $satuanList  = Satuan::orderBy('nama')->get();

        return view('admin.master-products.edit', compact('masterProduct', 'categories', 'satuanList'));
    }

    public function barcode(MasterProduct $masterProduct)
    {
        return view('admin.master-products.barcode', compact('masterProduct'));
    }

    public function update(Request $request, MasterProduct $masterProduct)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'barcode'     => 'nullable|string|max:100',
            'ukuran'      => 'nullable|string|max:100',
            'unit'        => 'required|string|max:50',
            'price'       => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        // Cek duplikat (kecuali record ini sendiri)
        $duplicate = MasterProduct::where('name', $data['name'])
            ->where('ukuran', $data['ukuran'] ?? null)
            ->where('unit', $data['unit'])
            ->where('id', '!=', $masterProduct->id)
            ->exists();

        if ($duplicate) {
            return back()->withInput()
                ->withErrors(['name' => 'Produk dengan nama, ukuran, dan satuan yang sama sudah terdaftar di master produk.']);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['price'] = $request->input('price');

        if (blank($data['barcode'] ?? null)) {
            $data['barcode'] = $masterProduct->barcode ?: sprintf('MPD-%06d', $masterProduct->id);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/master-products'), $filename);
            $data['image'] = 'master-products/' . $filename;
        }

        $masterProduct->update($data);

        return redirect()->route('admin.master-products.index')
            ->with('success', 'Master produk berhasil diperbarui.');
    }

    public function destroyImage(MasterProduct $masterProduct)
    {
        if ($masterProduct->image) {
            Storage::disk('public')->delete($masterProduct->image);
            $masterProduct->update(['image' => null]);
        }

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }

    public function destroy(MasterProduct $masterProduct)
    {
        $masterProduct->delete();

        return redirect()->route('admin.master-products.index')
            ->with('success', 'Master produk berhasil dihapus.');
    }

    /**
     * Endpoint JSON untuk autocomplete di form inbound.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');

        $results = MasterProduct::with(['category', 'satuan'])
            ->where('is_active', true)
            ->where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'barcode', 'ukuran', 'category_id', 'satuan_id', 'unit', 'price', 'image']);

        return response()->json($results);
    }
}
