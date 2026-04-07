<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->stock === 'low') {
            $query->whereColumn('stock', '<=', 'min_stock');
        } elseif ($request->stock === 'ok') {
            $query->whereColumn('stock', '>', 'min_stock');
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'unit'        => 'required|in:pcs,kg,liter',
            'description' => 'nullable|string',
            'expires_at'  => 'nullable|date',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sku'] = $this->generateNextSku((int) $data['category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'unit'        => 'required|in:pcs,kg,liter',
            'description' => 'nullable|string',
            'expires_at'  => 'nullable|date',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function nextSku(Category $category)
    {
        return response()->json([
            'sku' => $this->generateNextSku($category->id),
        ]);
    }

    private function generateNextSku(int $categoryId): string
    {
        $category = Category::findOrFail($categoryId);
        $prefix = $this->buildSkuPrefix($category->name);

        $lastSku = Product::where('category_id', $categoryId)
            ->where('sku', 'like', $prefix.'-%')
            ->orderByDesc('sku')
            ->value('sku');

        $nextNumber = 1;
        if ($lastSku && preg_match('/-(\d+)$/', $lastSku, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        $sku = sprintf('%s-%04d', $prefix, $nextNumber);
        while (Product::where('sku', $sku)->exists()) {
            $nextNumber++;
            $sku = sprintf('%s-%04d', $prefix, $nextNumber);
        }

        return $sku;
    }

    private function buildSkuPrefix(string $categoryName): string
    {
        $slug = Str::slug($categoryName);
        $parts = array_values(array_filter(explode('-', $slug)));

        if (count($parts) > 1) {
            $initials = implode('', array_map(static fn ($part) => Str::substr($part, 0, 1), $parts));
            $prefix = Str::upper(Str::substr($initials, 0, 3));
        } else {
            $prefix = Str::upper(Str::substr($slug, 0, 3));
        }

        return str_pad($prefix ?: 'PRD', 3, 'X');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
