# LJN Inventory Management System — Task Checklist

**Progress:** 100%

---

### Fase 0: Fix Task Instruction

- [x] ✅ Perbaiki `task-instruction.md` — judul proyek, referensi PRD/design `[Mudah]` (Selesai — `.agents/task-instruction.md`, `.agents/tasklist.md`)
- [x] ✅ Buat `tasklist.md` ini

### 1. Project Setup & Configuration

- [x] ✅ Konfigurasi `bootstrap/app.php` — middleware alias admin `[Mudah]` (Selesai — `bootstrap/app.php`, `app/Http/Middleware/EnsureUserIsAdmin.php`)
- [x] ✅ Setup Tailwind CSS v4 + Vite (konfirmasi styling) `[Mudah]` (Selesai — `resources/css/app.css`, `vite.config.js`)

### 2. Database & Migration

- [x] ✅ Migration `roles` table (id, name) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213325_create_roles_table.php`)
- [x] ✅ Migration tambah `role_id` ke users `[Mudah]` (Selesai — `database/migrations/2026_06_20_213331_add_role_id_to_users_table.php`)
- [x] ✅ Migration `barangs` (kode_barang, nama_barang, stok_gudang, stok_packing, total_stok, stock_threshold) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213326_create_barangs_table.php`)
- [x] ✅ Migration `suppliers` (kode_supplier, nama_supplier) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213327_create_suppliers_table.php`)
- [x] ✅ Migration `tokos` (kode_toko, nama_toko) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213328_create_tokos_table.php`)
- [x] ✅ Migration `barang_masuks` (barang_id, supplier_id, quantity) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213329_create_barang_masuks_table.php`)
- [x] ✅ Migration `transaksi_keluars` (barang_id, kode_toko_inputed, quantity, stok_awal_snapshot) `[Mudah]` (Selesai — `database/migrations/2026_06_20_213330_create_transaksi_keluars_table.php`)

### 3. Models & Relationships

- [x] ✅ Role model `[Mudah]` (Selesai — `app/Models/Role.php`)
- [x] ✅ Update User model — relasi `belongsTo` Role + `isAdmin()` `[Mudah]` (Selesai — `app/Models/User.php`)
- [x] ✅ Barang model `[Mudah]` (Selesai — `app/Models/Barang.php`)
- [x] ✅ Supplier model `[Mudah]` (Selesai — `app/Models/Supplier.php`)
- [x] ✅ Toko model `[Mudah]` (Selesai — `app/Models/Toko.php`)
- [x] ✅ BarangMasuk model `[Mudah]` (Selesai — `app/Models/BarangMasuk.php`)
- [x] ✅ TransaksiKeluar model `[Mudah]` (Selesai — `app/Models/TransaksiKeluar.php`)
- [x] ✅ Factory + Seeder (roles, admin user) `[Mudah]` (Selesai — `database/factories/*`, `database/seeders/DatabaseSeeder.php`)

### 4. Backend Logic & Controllers

- [x] ✅ AuthController (login, logout, rate limiting) `[Sedang]` (Selesai — `app/Http/Controllers/AuthController.php`)
- [x] ✅ DashboardController (stock alert, recent tx, statistik) `[Sedang]` (Selesai — `app/Http/Controllers/DashboardController.php`)
- [x] ✅ BarangController (CRUD master barang) `[Sedang]` (Selesai — `app/Http/Controllers/BarangController.php`)
- [x] ✅ BarangMasukController (inbound + update stok) `[Sedang]` (Selesai — `app/Http/Controllers/BarangMasukController.php`)
- [x] ✅ TransaksiController (outbound + validasi stok + snapshot) `[Sedang]` (Selesai — `app/Http/Controllers/TransaksiController.php`)
- [x] ✅ Admin: User/Toko/Supplier controllers `[Sedang]` (Selesai — `app/Http/Controllers/Admin/*.php`)
- [x] ✅ Form Request validation classes `[Mudah]` (Selesai — `app/Http/Requests/*.php`)
- [x] ✅ Routes definition `[Mudah]` (Selesai — `routes/web.php`)

### 5. Security

- [x] ✅ Middleware `EnsureUserIsAdmin` `[Mudah]` (Selesai — Fase 1)
- [x] ✅ Rate limiter login `[Mudah]` (Selesai — `routes/web.php`: throttle:5,1)
- [x] ✅ CSRF + XSS (Blade `{{ }}`) — bawaan Laravel

### 6. Frontend & Blade Views

- [x] ✅ Layout utama (LJN branding) `[Sedang]` (Selesai — `resources/views/layouts/app.blade.php`)
- [x] ✅ Login page `[Mudah]` (Selesai — `resources/views/auth/login.blade.php`)
- [x] ✅ Dashboard `[Sedang]` (Selesai — `resources/views/dashboard.blade.php`)
- [x] ✅ Barang index / create / edit `[Sedang]` (Selesai — `resources/views/barangs/*.blade.php`)
- [x] ✅ Barang Masuk form `[Mudah]` (Selesai — `resources/views/barang-masuk/create.blade.php`)
- [x] ✅ Transaksi form (+ auto-fill stok) `[Sedang]` (Selesai — `resources/views/transaksi/create.blade.php`)
- [x] ✅ Admin: Users management `[Sedang]` (Selesai — `resources/views/admin/users/*.blade.php`)
- [x] ✅ Admin: Toko management `[Sedang]` (Selesai — `resources/views/admin/tokos/*.blade.php`)
- [x] ✅ Admin: Supplier management `[Sedang]` (Selesai — `resources/views/admin/suppliers/*.blade.php`)
- [x] ✅ Toast notification component `[Mudah]` (Selesai — di layout `app.blade.php`: session success + error)

### 7. JavaScript

- [x] ✅ Axios auto-fill stok saat pilih barang di transaksi `[Mudah]` (Selesai — `resources/js/transaksi.js`)
- [x] ✅ Auto-fill kode_toko dari dropdown `[Mudah]` (Selesai — `resources/js/transaksi.js`)

### 8. Final Polish & Bug Fixes

- [x] ✅ Linting (Pint) `[Mudah]` (Selesai — 10 issues fixed)
- [x] ✅ Vite build `[Mudah]` (Selesai — build sukses)
- [x] ✅ Test suite `[Mudah]` (Selesai — 2/2 passed)
