<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — LJN Inventory</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-neutral text-primary font-sans antialiased min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm mx-4">
        <div class="text-center mb-8">
            <h1 class="font-mono text-display text-secondary tracking-tight">LJN Inventory</h1>
            <p class="font-mono text-xs text-primary/50 mt-2">Inventory Management System</p>
        </div>

        <div class="bg-surface border border-secondary/10 rounded-lg p-8">
            @if (session('warning'))
            <div class="mb-4 p-3 bg-tertiary/10 border border-tertiary/30 rounded-md text-xs text-primary">
                {{ session('warning') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="toko_id" class="font-mono text-xs text-primary/70 block mb-1.5">Toko</label>
                    <select id="toko_id" name="toko_id" required
                        class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                        <option value="">— Pilih Toko —</option>
                        @foreach ($tokos as $toko)
                        <option value="{{ $toko->id }}" {{ old('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->kode_toko }} — {{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="email" class="font-mono text-xs text-primary/70 block mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                </div>

                <div class="mb-6">
                    <label for="password" class="font-mono text-xs text-primary/70 block mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-3 py-2.5 border border-primary/20 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-secondary/30 focus:border-secondary">
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 bg-tertiary text-on-primary font-mono text-sm rounded-md hover:opacity-90 transition-opacity">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
