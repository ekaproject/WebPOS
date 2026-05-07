<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MasterProduct;
use Illuminate\Http\Request;

class MasterProductController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterProduct::with('category')->orderBy('name');

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

        return view('admin.master-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit'        => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/master-products'), $filename);
            $data['image'] = 'master-products/' . $filename;
        }

        MasterProduct::create($data);

        return redirect()->route('admin.master-products.index')
            ->with('success', 'Master produk berhasil ditambahkan.');
    }

    public function edit(MasterProduct $masterProduct)
    {
        $categories = Category::query()->visibleForMenu()->where('is_active', true)->orderBy('name')->get();

        return view('admin.master-products.edit', compact('masterProduct', 'categories'));
    }

    public function update(Request $request, MasterProduct $masterProduct)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit'        => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

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

        $results = MasterProduct::with('category')
            ->where('is_active', true)
            ->where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'category_id', 'unit']);

        return response()->json($results);
    }
}
