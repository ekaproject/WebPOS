<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->visibleForMenu()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'type'        => 'required|in:fmcg,fresh,digital,fnb',
            'is_active'   => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $count = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $count++;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'type'        => 'required|in:fmcg,fresh,digital,fnb',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Regenerate slug only if name changed
        if ($category->name !== $data['name']) {
            $slug = Str::slug($data['name']);
            $original = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $original . '-' . $count++;
            }
            $data['slug'] = $slug;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
