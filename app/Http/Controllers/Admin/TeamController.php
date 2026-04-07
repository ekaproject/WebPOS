<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($builder) use ($request) {
                $builder->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $members = $query->latest()->paginate(12)->withQueryString();

        return view('admin.team.index', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'supervisor', 'cashier', 'staff'])],
            'phone' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');

        User::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function update(Request $request, User $team)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($team->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'supervisor', 'cashier', 'staff'])],
            'phone' => 'nullable|string|max:30',
            'is_active' => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $team->password = Hash::make($data['password']);
        }

        unset($data['password']);
        $data['is_active'] = $request->boolean('is_active');

        $team->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(User $team)
    {
        if ((int) auth()->id() === (int) $team->id) {
            return redirect()->route('admin.team.index')->with('success', 'Akun yang sedang login tidak bisa dihapus.');
        }

        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
