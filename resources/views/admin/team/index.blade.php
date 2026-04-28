@extends('layouts.admin')

@section('title', 'Tim')

@section('content')
@php
    $showCreateForm = request()->boolean('show_create') || $errors->has('name') || $errors->has('email') || $errors->has('role') || $errors->has('phone') || $errors->has('password');
    $editMemberId = (int) request('edit_member');
@endphp
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Form User</h1>
            <p class="text-on-surface-variant mt-1">Kelola akun user, role, dan status aktif dalam satu halaman.</p>
        </div>
        <a href="{{ route('admin.team.index', ['show_create' => 1]) }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
            + Tambah User
        </a>
    </div>

    <form method="GET" action="{{ route('admin.team.index') }}" class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau nomor..."
                class="md:col-span-2 w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            <select name="role" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                <option value="">Semua Role</option>
                @foreach(['admin' => 'Admin', 'supervisor' => 'Supervisor', 'cashier' => 'Kasir', 'staff' => 'Staff'] as $value => $label)
                    <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="is_active" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="mt-3 flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Filter User</button>
            <a href="{{ route('admin.team.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">Reset</a>
        </div>
    </form>

    <form id="create-team-form" action="{{ route('admin.team.store') }}" method="POST" class="{{ $showCreateForm ? '' : 'hidden' }} bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-6">
        @csrf
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-headline font-extrabold text-on-surface">Tambah Anggota User</h2>
            <a href="{{ route('admin.team.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">Tutup</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Role</label>
                <select name="role" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Kasir</option>
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                </select>
                @error('role')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('phone')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Password</label>
                <input type="password" name="password" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('password')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div class="md:col-span-2 flex items-center gap-2">
                <input type="checkbox" id="is_active_create" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-outline-variant focus:ring-primary"/>
                <label for="is_active_create" class="text-sm font-medium text-on-surface">Akun aktif</label>
            </div>
        </div>
        <button type="submit" class="mt-5 px-6 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Simpan Anggota</button>
    </form>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        @forelse($members as $member)
            <article class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-5">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">{{ $member->name }}</h3>
                        <p class="text-sm text-on-surface-variant">{{ $member->email }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $member->is_active ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-error' }}">
                            {{ $member->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <a href="{{ route('admin.team.index', ['edit_member' => $member->id]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-primary">
                            Edit
                        </a>
                    </div>
                </div>

                <form id="edit-team-{{ $member->id }}" method="POST" action="{{ route('admin.team.update', $member) }}" class="{{ $editMemberId === $member->id ? '' : 'hidden' }} mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary" required/>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary" required/>
                    <select name="role" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary" required>
                        @foreach(['admin' => 'Admin', 'supervisor' => 'Supervisor', 'cashier' => 'Kasir', 'staff' => 'Staff'] as $value => $label)
                            <option value="{{ $value }}" {{ $member->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="phone" value="{{ old('phone', $member->phone) }}" placeholder="No. telepon" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                    <input type="password" name="password" placeholder="Password baru (opsional)" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                    <label class="md:col-span-2 flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" {{ $member->is_active ? 'checked' : '' }} class="w-4 h-4 text-primary rounded border-outline-variant focus:ring-primary"/>
                        Akun aktif
                    </label>
                    <div class="md:col-span-2 flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary">Update</button>
                        <a href="{{ route('admin.team.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant">Batal</a>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.team.destroy', $member) }}" class="mt-3" onsubmit="return confirm('Hapus anggota ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-error hover:underline">Hapus Anggota</button>
                </form>
            </article>
        @empty
            <div class="xl:col-span-2 bg-surface-container-low rounded-2xl border border-outline-variant/20 p-8 text-center text-on-surface-variant">
                Belum ada anggota tim.
            </div>
        @endforelse
    </div>

    <div>
        {{ $members->links() }}
    </div>
</div>
@endsection
