<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LJN Inventory')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral text-primary font-sans antialiased">
    @auth
    <nav class="bg-surface border-b border-secondary/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="font-mono text-lg font-medium tracking-tight text-secondary">LJN Inventory</a>
                    <div class="hidden md:flex space-x-6">
                        <div class="relative group">
                            <a href="{{ route('dashboard') }}" class="font-mono text-sm {{ request()->routeIs('dashboard') ? 'text-tertiary' : 'text-primary hover:text-secondary' }}">Dashboard</a>
                        </div>
                        <div class="relative group">
                            <a href="{{ route('barangs.index') }}" class="font-mono text-sm {{ request()->routeIs('barangs.*') ? 'text-tertiary' : 'text-primary hover:text-secondary' }}">Barang</a>
                        </div>
                        <div class="relative group">
                            <span class="font-mono text-sm {{ request()->routeIs('barang-masuk.*') ? 'text-tertiary' : 'text-primary hover:text-secondary' }} cursor-pointer">Barang Masuk</span>
                            <div class="absolute top-full left-0 mt-1 w-44 bg-surface border border-secondary/10 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all py-1 z-50">
                                <a href="{{ route('barang-masuk.create') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('barang-masuk.create') || request()->routeIs('barang-masuk.store') ? 'text-tertiary' : '' }}">Dari Supplier</a>
                                <a href="{{ route('barang-masuk.packing') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('barang-masuk.packing*') ? 'text-tertiary' : '' }}">Ke Packing</a>
                            </div>
                        </div>
                        <div class="relative group">
                            <span class="font-mono text-sm {{ request()->routeIs('transaksi.*') ? 'text-tertiary' : 'text-primary hover:text-secondary' }} cursor-pointer">Transaksi</span>
                            <div class="absolute top-full left-0 mt-1 w-44 bg-surface border border-secondary/10 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all py-1 z-50">
                                <a href="{{ route('transaksi.create') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('transaksi.create') || request()->routeIs('transaksi.store') ? 'text-tertiary' : '' }}">Baru</a>
                                <a href="{{ route('transaksi.history') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('transaksi.history') ? 'text-tertiary' : '' }}">Riwayat</a>
                            </div>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="relative group">
                            <span class="font-mono text-sm {{ request()->routeIs('admin.*') ? 'text-tertiary' : 'text-primary hover:text-secondary' }} cursor-pointer">Admin</span>
                            <div class="absolute top-full left-0 mt-1 w-40 bg-surface border border-secondary/10 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all py-1 z-50">
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('admin.users.*') ? 'text-tertiary' : '' }}">Users</a>
                                <a href="{{ route('admin.tokos.index') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('admin.tokos.*') ? 'text-tertiary' : '' }}">Toko</a>
                                <a href="{{ route('admin.suppliers.index') }}" class="block px-4 py-2 font-mono text-xs text-primary hover:bg-neutral {{ request()->routeIs('admin.suppliers.*') ? 'text-tertiary' : '' }}">Supplier</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="font-mono text-xs text-primary/60 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="font-mono text-xs text-secondary hover:text-tertiary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
        <div class="mb-6 p-4 bg-tertiary/10 border border-tertiary/30 rounded-md text-sm text-primary">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
