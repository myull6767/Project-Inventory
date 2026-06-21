<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LJN Inventory')</title>
    <script>if(localStorage.getItem('dark-mode')==='true')document.documentElement.classList.add('dark')</script>
    <link rel="icon" type="image/x-icon" href="{{asset('logo-ljn.png')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral text-primary font-sans antialiased">
    @auth
    <nav class="bg-surface border-b border-secondary/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="font-mono text-lg font-medium tracking-tight text-secondary"><img src="{{asset('logo-ljn.png')}}" alt="navbar brand"
                                class="navbar-brand h-16 w-auto object-contain" /></a>
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
                <div class="flex items-center gap-3">
                    <button id="dark-toggle" type="button" class="inline-flex items-center justify-center text-primary/50 hover:text-secondary transition-colors" title="Toggle dark mode">
                        <svg id="sun-icon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg id="moon-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                    <span class="font-mono text-xs text-primary/60 leading-none">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="leading-none">
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

    <script>
        (function() {
            var toggle = document.getElementById('dark-toggle');
            var sun = document.getElementById('sun-icon');
            var moon = document.getElementById('moon-icon');
            if (!toggle) return;
            function setDark(dark) {
                if (dark) {
                    document.documentElement.classList.add('dark');
                    sun.classList.remove('hidden');
                    moon.classList.add('hidden');
                } else {
                    document.documentElement.classList.remove('dark');
                    sun.classList.add('hidden');
                    moon.classList.remove('hidden');
                }
                localStorage.setItem('dark-mode', dark ? 'true' : 'false');
            }
            setDark(document.documentElement.classList.contains('dark'));
            toggle.addEventListener('click', function() {
                setDark(!document.documentElement.classList.contains('dark'));
            });
        })();
    </script>
</body>
</html>
