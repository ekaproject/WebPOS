<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\Product;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $query = Distributor::withCount('products');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('code', 'like', '%'.$request->search.'%')
                  ->orWhere('contact_person', 'like', '%'.$request->search.'%');
            });
        }

        $distributors = $query->latest()->paginate(20);

        return view('admin.distributors.index', compact('distributors'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.distributors.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:distributors,code',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'is_active'      => 'boolean',
            'products'       => 'nullable|array',
            'products.*.id'  => 'required|exists:products,id',
            'products.*.purchase_price' => 'required|numeric|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $distributor = Distributor::create($data);

        if (!empty($data['products'])) {
            $sync = [];
            foreach ($data['products'] as $p) {
                $sync[$p['id']] = ['purchase_price' => $p['purchase_price']];
            }
            $distributor->products()->sync($sync);
        }

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function show(Distributor $distributor)
    {
        $distributor->load('products');
        return view('admin.distributors.show', compact('distributor'));
    }

    public function edit(Distributor $distributor)
    {
        $distributor->load('products');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.distributors.edit', compact('distributor', 'products'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'code'           => 'required|string|max:50|unique:distributors,code,'.$distributor->id,
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'is_active'      => 'boolean',
            'products'       => 'nullable|array',
            'products.*.id'  => 'required|exists:products,id',
            'products.*.purchase_price' => 'required|numeric|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $distributor->update($data);

        $sync = [];
        if (!empty($data['products'])) {
            foreach ($data['products'] as $p) {
                $sync[$p['id']] = ['purchase_price' => $p['purchase_price']];
            }
        }
        $distributor->products()->sync($sync);

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributor berhasil diperbarui.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->products()->detach();
        $distributor->delete();

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributor berhasil dihapus.');
    }
}
