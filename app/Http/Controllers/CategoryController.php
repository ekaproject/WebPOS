<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->visibleForMenu()
            ->where('is_active', true)
            ->withCount('products');

        if ($request->filled('search')) {
            $keyword = trim((string) $request->search);

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%')
                    ->orWhereHas('products', function ($productQuery) use ($keyword) {
                        $productQuery->where('is_active', true)
                            ->where(function ($p) use ($keyword) {
                                $p->where('name', 'like', '%'.$keyword.'%')
                                  ->orWhere('sku', 'like', '%'.$keyword.'%');
                            });
                    });
            });
        }

        $categories = $query->orderBy('name')->get();

        return view('categories', compact('categories'));
    }

    public function show(Request $request, Category $category)
    {
        if ($category->slug === 'inventory-qc') {
            abort(404);
        }

        $query = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('name')->paginate(12);
        $categories = Category::query()
            ->visibleForMenu()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('category', compact('category', 'products', 'categories'));
    }
}
