@extends('layouts.app')

@section('title', 'Manage Users — LJN Inventory')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="font-mono text-lg text-secondary">Manage Users</h1>
    <a href="{{ route('admin.users.create') }}" class="py-2 px-4 bg-tertiary text-on-primary font-mono text-xs rounded-md hover:opacity-90">Tambah User</a>
</div>

<div class="bg-surface border border-secondary/10 rounded-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-secondary/10 font-mono text-xs text-primary/60">
                <th class="text-left py-3 px-4">Nama</th>
                <th class="text-left py-3 px-4">Email</th>
                <th class="text-left py-3 px-4">Role</th>
                <th class="text-right py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr class="border-b border-primary/5">
                <td class="py-3 px-4">{{ $user->name }}</td>
                <td class="py-3 px-4 font-mono text-xs">{{ $user->email }}</td>
                <td class="py-3 px-4 font-mono text-xs">{{ $user->role->name ?? '-' }}</td>
                <td class="py-3 px-4 text-right flex items-center justify-end gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="py-1 px-2 font-mono text-xs rounded border border-secondary/20 text-secondary hover:bg-secondary/5">Edit</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="Hapus user {{ $user->name }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-1 px-2 font-mono text-xs rounded border border-red-200 text-red-400 hover:bg-red-50">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-6 text-center text-xs text-primary/50">Belum ada user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
