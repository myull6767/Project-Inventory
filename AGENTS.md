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
| `composer run setup` | Full project setup (composer install, .env, key, migrate, npm --ignore-scripts, build) |
| `composer run dev` | Concurrent dev servers (artisan serve, queue:listen, pail logs, Vite) |
| `composer run test` | Runs `config:clear` then `php artisan test` |
| `npm run build` | Vite production build |
| `npm run dev` | Vite dev server |
| `php artisan make:model -m -c --resource --requests --policy` | Default model creation pattern |
| `./vendor/bin/pint` | Lint PHP code |

Order: `lint (pint) -> typecheck (none) -> test`

## Architecture

### Current state (not skeleton — app is partially built)
- **RBAC**: `roles` table, `role_id` FK on `users`, `admin` middleware alias (`EnsureUserIsAdmin`), `User::isAdmin()` method
- **Multi-location stock**: `Barang` has `stok_gudang`, `stok_packing`, `total_stok`, `stock_threshold` columns
- **Inbound (`BarangMasuk`)**: two types — "from supplier" (adds to gudang) and "to packing" (moves gudang→packing); `type` column (`supplier`/`packing`)
- **Outbound (`TransaksiKeluar`)**: deducts from `total_stok` (both gudang+packing simultaneously); tracks `toko_id`
- **Admin panel** (`/admin/*`): CRUD for users, tokos, suppliers — gated by `admin` middleware
- **Security**: `AddSecurityHeaders` global middleware sets CSP with nonces, HSTS-style headers, removes `X-Powered-By`; CSP nonce shared via `$cspNonce` view variable
- **Routes**: `routes/web.php` only (no API routes). Login throttled at 5 attempts/min. Guest routes: login. Auth routes: dashboard, barangs CRUD, barang-masuk (2 flows), transaksi (create/history/destroy), admin group.
- **Vite inputs**: `resources/css/app.css`, `resources/js/app.js`, `resources/js/transaksi.js`, `resources/js/barang-masuk.js`
- **Models**: `User`, `Role`, `Barang`, `BarangMasuk`, `TransaksiKeluar`, `Supplier`, `Toko`
- **Views**: 20+ Blade files under `layouts/`, `auth/`, `barangs/`, `barang-masuk/`, `transaksi/`, `admin/{users,tokos,suppliers}/`
- **Migrations**: 12 total (users, cache, jobs, roles, barangs, suppliers, tokos, barang_masuks, transaksi_keluars, add_role_id, add_type to barang_masuks, add_user_id to transaksi_keluars)
- **No tests yet** for project code (only Laravel boilerplate ExampleTest files)

## Conventions

- **No public registration** — accounts created via admin panel or DB only
- **No Pest** — PHPUnit (Unit + Feature suites); in-memory SQLite per `phpunit.xml`
- **PHP attribute syntax** (`#[Fillable]`, `#[Hidden]`) preferred over docblock properties (see `User.php`)
- **Frontend**: JetBrains Mono (mono) + Inter (sans), Tailwind v4. Dark mode toggle persisted in `localStorage('dark-mode')`. LJN branding: `#FF6600` (orange), `#0033CC` (blue) — mapped as `secondary`/`tertiary` in `app.css`
- Form validation: quantity must be int > 0; outbound cannot exceed stock (check `total_stok`)
- `.npmrc` has `ignore-scripts=true` — always run `npm install --ignore-scripts`
- Indentation: 4 spaces PHP, 2 spaces YAML (per `.editorconfig`)
- Linter: Laravel Pint (`./vendor/bin/pint`) — no custom config file, uses defaults

## Testing

```bash
composer run test                   # full test suite (config:clear + php artisan test)
php artisan test --filter=SomeTest  # single test
php artisan test tests/Unit/SomeTest.php
```

- Tests use in-memory SQLite (configured in `phpunit.xml`)
- Only boilerplate examples exist — no project tests written yet

## Key files

| Path | Purpose |
|---|---|
| `.agents/prd.md` | Product requirements — read before implementing features |
| `.agents/design.md` | Design tokens, typography, color palette — follow for all UI |
| `.env` | Active config (MySQL: `invenv2` DB) |
| `phpunit.xml` | Test config (in-memory SQLite) |
| `composer.json` | Scripts: `setup`, `dev`, `test` |
| `bootstrap/app.php` | Middleware registration (global `AddSecurityHeaders`, alias `admin`) |
| `routes/web.php` | All routes — no API routes yet |
