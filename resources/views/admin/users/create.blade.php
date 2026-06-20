@extends('layouts.app')

@section('title', 'Tambah User — LJN Inventory')

@section('content')
<div class="max-w-lg">
    <h1 class="font-mono text-lg text-secondary mb-6">Tambah User</h1>

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-surface border border-secondary/10 rounded-lg p-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="font-mono text-xs text-primary/70 block mb-1.5">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="email" class="font-mono text-xs text-primary/70 block mb-1.5">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="password" class="font-mono text-xs text-primary/70 block mb-1.5">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
        </div>

        <div>
            <label for="role_id" class="font-mono text-xs text-primary/70 block mb-1.5">Role</label>
            <select id="role_id" name="role_id" required
                class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                <option value="">— Pilih Role —</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="py-2.5 px-6 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
            Simpan
        </button>
    </form>
</div>
@endsection
