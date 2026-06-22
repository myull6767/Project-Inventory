# AGENTS.md — LJN Inventory Management System

## Stack
- **Backend:** Laravel 13 + PHP 8.3
- **Frontend:** Tailwind CSS v4 + Blade + Vanilla JS (Axios) — no React/Vue/Alpine
- **Database:** MySQL (`.env`), SQLite default in `.env.example`
- **Build:** Vite (`@tailwindcss/vite` plugin, `laravel-vite-plugin`)
- **Auth:** Session-based (database driver), custom `AuthController`
- **Queue/Cache:** Database driver

## Commands

| Command | What it does |
|---|---|
| `composer run setup` | Full setup: composer install, .env, key, migrate, npm --ignore-scripts, build |
| `composer run dev` | Concurrent: `php artisan serve`, `queue:listen`, `pail` logs, Vite |
| `composer run test` | `config:clear` then `php artisan test` |
| `npm run build` | Vite production build |
| `npm run dev` | Vite dev server |
| `php artisan make:model -m -c --resource --requests --policy` | Default model creation pattern |
| `./vendor/bin/pint` | Lint PHP code (Laravel Pint, default config) |

Order: `lint (pint) -> test`

## Architecture

- **Multi-toko**: 3 stores (ANNA WIFI, PELAUKAN, VILLA KENCANA). User selects toko at login; stored in `session('toko_id')`. `EnsureTokoSelected` middleware (`toko` alias) on all auth routes.
- **Per-toko stock**: `barang_toko` pivot table `(barang_id, toko_id, stok_gudang, stok_packing, total_stok, stock_threshold)` — unique per `(barang_id, toko_id)`. `Barang` is a master product catalog (no stock columns). Query stock via `BarangToko` model or `Barang::tokos()`.
- **RBAC**: `roles` table, `role_id` FK on `users`, `admin` middleware alias (`EnsureUserIsAdmin`), `User::isAdmin()` checks `role->name === 'admin'`
- **Stock flow**: Inbound `type='supplier'` adds to `stok_gudang` + `total_stok`; `type='packing'` moves `stok_gudang` → `stok_packing` (`total_stok` unchanged). Outbound deducts `stok_packing` + `total_stok`; requires both `total_stok` AND `stok_packing` >= quantity. All stock operations scoped by `toko_id`.
- **Destroy does NOT reverse stock** — deleting a `BarangMasuk`, `Transaksi`, or `TransaksiKeluar` does not revert stock adjustments
- **Migrations**: 14 total (includes `cache`, `jobs`, `sessions` tables)
- **Models** (8): `User`, `Role`, `Barang`, `BarangMasuk`, `Transaksi`, `TransaksiKeluar`, `Supplier`, `Toko` — each has a Factory
- **Views**: 20+ Blade files under `layouts/`, `auth/`, `barangs/`, `barang-masuk/`, `transaksi/`, `admin/{users,tokos,suppliers}/`
- **Routes**: `routes/web.php` only (no API routes). Login throttled at 5 attempts/min (`throttle:5,1`). Guest: login. Auth: dashboard, barangs CRUD, barang-masuk (2 flows), transaksi (create/history/cetak/destroy), admin group (`/admin/*`, middleware: `admin`).
- **Security**: `AddSecurityHeaders` global middleware sets CSP with nonces, removes `X-Powered-By`; nonce shared via `$cspNonce` view variable. All inline `<script>` tags must include `nonce="{{ $cspNonce }}"`.
- **Admin panel** (`/admin/*`): CRUD for users, tokos, suppliers — gated by `admin` middleware alias
- **Print**: `GET /transaksi/{transaksi}/cetak` renders receipt view that auto-triggers `window.print()`
- **Vite inputs**: `resources/css/app.css`, `resources/js/app.js`, `resources/js/transaksi.js`, `resources/js/barang-masuk.js`
- **Tests**: Only Laravel boilerplate `ExampleTest` files exist (Unit + Feature)
- **Timezone**: History views use `Asia/Jakarta` with UTC conversion for date filtering

## Conventions

- **No public registration** — accounts created via admin panel or DB only
- **No Pest** — PHPUnit (Unit + Feature suites); in-memory SQLite per `phpunit.xml`
- **PHP attribute syntax** (`#[Fillable]`, `#[Hidden]`) preferred over docblock
- **Form Request validation** — every controller uses a dedicated `*Request` class
- **Tailwind v4 CSS-first** — no `tailwind.config.js`; theme defined in `resources/css/app.css` via `@theme {}`; `@source` directives scan `vendor/` and `storage/` for classes
- **Dark mode**: toggled via `localStorage('dark-mode')`; `.dark` class on `<html>`; `@custom-variant dark` in `app.css`
- **LJN branding**: `--color-secondary: #0033CC` (blue), `--color-tertiary: #FF6600` (orange), `--color-primary: #1A1A1A`
- **Fonts**: Inter (sans) body, JetBrains Mono (mono) headings/labels
- **JS behaviors** (vanilla JS in `app.js`): `data-auto-submit` attribute auto-submits `<form>` on `change`; `data-confirm` shows `confirm()` dialog on submit
- **Custom sort** on `Barang` index: `?sort=nama`, `?sort=kode_asc`, `?sort=kode_desc` (parses numeric suffix after `-` in `kode_barang`)
- **Quantity validation**: int > 0; outbound checks `stok_packing >= quantity` in controller (not form request)
- **`.npmrc` has `ignore-scripts=true`** — always use `npm install --ignore-scripts`
- **Indentation**: 4 spaces PHP, 2 spaces YAML (`.editorconfig`)

## Testing

```bash
composer run test                   # full suite (config:clear + php artisan test)
php artisan test --filter=SomeTest  # single test
php artisan test tests/Unit/SomeTest.php
```

- In-memory SQLite (configured in `phpunit.xml`)
- Factories exist for all 8 models — use them when writing tests
- `.phpunit.result.cache` is gitignored

## Key files

| Path | Purpose |
|---|---|
| `.agents/prd.md` | Product requirements — read before implementing features |
| `.agents/design.md` | Design tokens, typography, color palette — follow for all UI |
| `.env` | Active config (MySQL: `invenv2` DB) |
| `phpunit.xml` | Test config (in-memory SQLite) |
| `composer.json` | Scripts: `setup`, `dev`, `test` |
| `bootstrap/app.php` | Middleware registration (global `AddSecurityHeaders`, alias `admin`, alias `toko`) |
| `routes/web.php` | All routes — no API routes |
| `database/factories/` | 9 factories (includes `BarangTokoFactory`) |
| `resources/css/app.css` | Tailwind v4 theme and variants |
