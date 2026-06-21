# AGENTS.md — LJN Inventory Management System

## Stack
- **Backend:** Laravel 13 + PHP 8.3
- **Frontend:** Tailwind CSS v4 + Blade + Vanilla JS (Axios)
- **Database:** MySQL (`.env`), SQLite default in `.env.example`
- **Build:** Vite (`@tailwindcss/vite` plugin, `laravel-vite-plugin`)
- **Auth:** Session-based (database driver), custom `AuthController`
- **Queue/Cache:** Database driver

## Commands

| Command | What it does |
|---|---|
| `composer run setup` | Full setup: composer install, .env, key, migrate, npm --ignore-scripts, build |
| `composer run dev` | Concurrent: `php artisan serve`, `queue:listen`, `pail` logs, Vite (via `npx concurrently`) |
| `composer run test` | `config:clear` then `php artisan test` |
| `npm run build` | Vite production build |
| `npm run dev` | Vite dev server |
| `php artisan make:model -m -c --resource --requests --policy` | Default model creation pattern |
| `./vendor/bin/pint` | Lint PHP code |

Order: `lint (pint) -> test`

## Architecture

### Current state (partially built, not skeleton)
- **RBAC**: `roles` table, `role_id` FK on `users`, `admin` middleware alias (`EnsureUserIsAdmin`), `User::isAdmin()` checks `role->name === 'admin'`
- **Multi-location stock**: `Barang` has `stok_gudang`, `stok_packing`, `total_stok`, `stock_threshold` columns
- **Inbound (`BarangMasuk`)**: two types — `type='supplier'` (adds to `stok_gudang` + `total_stok`) and `type='packing'` (moves `stok_gudang` → `stok_packing`, `total_stok` unchanged)
- **Outbound — header/detail**: `Transaksi` (header) stores `kode_toko_inputed` + `user_id`; `TransaksiKeluar` (detail) stores `barang_id`, `quantity`, `stok_awal_snapshot`, `transaksi_id` FK. Deducts from `stok_packing` + `total_stok`; checks both `total_stok` AND `stok_packing` per item (packing must have enough).
- **Destroy does NOT reverse stock** — deleting a `BarangMasuk`, `Transaksi`, or `TransaksiKeluar` does not revert the stock adjustments
- **Print**: `GET /transaksi/{transaksi}/cetak` renders a receipt-style print view that auto-triggers `window.print()`
- **Admin panel** (`/admin/*`): CRUD for users, tokos, suppliers — gated by `admin` middleware
- **Security**: `AddSecurityHeaders` global middleware sets CSP with nonces, HSTS-style headers, removes `X-Powered-By`; CSP nonce shared via `$cspNonce` view variable
- **Routes**: `routes/web.php` only (no API routes). Login throttled at 5 attempts/min. Guest routes: login. Auth routes: dashboard, barangs CRUD, barang-masuk (2 flows), transaksi (create/history/cetak/destroy), admin group.
- **Vite inputs**: `resources/css/app.css`, `resources/js/app.js`, `resources/js/transaksi.js`, `resources/js/barang-masuk.js`
- **Models** (8): `User`, `Role`, `Barang`, `BarangMasuk`, `Transaksi`, `TransaksiKeluar`, `Supplier`, `Toko` — each with a Factory
- **Views**: 20+ Blade files under `layouts/`, `auth/`, `barangs/`, `barang-masuk/`, `transaksi/`, `admin/{users,tokos,suppliers}/`
- **Migrations**: 14 total
- **Tests**: Only Laravel boilerplate `ExampleTest` files exist (Unit + Feature)

## Conventions
- **No public registration** — accounts created via admin panel or DB only
- **No Pest** — PHPUnit (Unit + Feature suites); in-memory SQLite per `phpunit.xml`
- **PHP attribute syntax** (`#[Fillable]`, `#[Hidden]`) preferred over docblock properties
- **Form Request validation** — every controller uses a dedicated `*Request` class (`LoginRequest`, `BarangRequest`, `BarangMasukRequest`, `TransaksiRequest`)
- **Frontend**: Inter (sans) + JetBrains Mono (mono), Tailwind v4. Dark mode toggled via `localStorage('dark-mode')`. LJN branding colors: `--color-secondary: #0033CC` (blue), `--color-tertiary: #FF6600` (orange) in `app.css`
- Form validation: quantity must be int > 0; outbound requires stok_packing >= quantity
- `.npmrc` has `ignore-scripts=true` — always run `npm install --ignore-scripts`
- Indentation: 4 spaces PHP, 2 spaces YAML (`.editorconfig`)
- Linter: Laravel Pint (`./vendor/bin/pint`) — no custom config, uses defaults

## Testing

```bash
composer run test                   # full suite (config:clear + php artisan test)
php artisan test --filter=SomeTest  # single test
php artisan test tests/Unit/SomeTest.php
```

- In-memory SQLite (configured in `phpunit.xml`)
- Factories exist for all 7 models — use them when writing tests

## Key files

| Path | Purpose |
|---|---|
| `.agents/prd.md` | Product requirements — read before implementing features |
| `.agents/design.md` | Design tokens, typography, color palette — follow for all UI |
| `.env` | Active config (MySQL: `invenv2` DB) |
| `phpunit.xml` | Test config (in-memory SQLite) |
| `composer.json` | Scripts: `setup`, `dev`, `test` |
| `bootstrap/app.php` | Middleware registration (global `AddSecurityHeaders`, alias `admin`) |
| `routes/web.php` | All routes — no API routes |
| `database/factories/` | 7 factories, one per model |
