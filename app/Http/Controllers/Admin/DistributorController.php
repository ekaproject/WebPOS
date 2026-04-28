<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $query = Distributor::withCount('inboundItems');

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
        return view('admin.distributors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255|unique:distributors,name',
            'code'           => 'required|string|max:50|unique:distributors,code',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'is_active'      => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Distributor::create($data);

        return redirect()->route('admin.distributors.index')
            ->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function show(Distributor $distributor)
    {
        return view('admin.distributors.show', compact('distributor'));
    }

    public function edit(Distributor $distributor)
    {
        return view('admin.distributors.edit', compact('distributor'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255|unique:distributors,name,'.$distributor->id,
            'code'           => 'required|string|max:50|unique:distributors,code,'.$distributor->id,
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'is_active'      => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $distributor->update($data);

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
