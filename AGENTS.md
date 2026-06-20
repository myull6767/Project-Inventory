# AGENTS.md — LJN Inventory Management System

## Stack
- **Backend:** Laravel 13 + PHP 8.3
- **Frontend:** Tailwind CSS v4 + Blade + Vanilla JS (Axios)
- **Database:** MySQL (config in `.env`; `.env.example` defaults to SQLite)
- **Build:** Vite via `laravel-vite-plugin`

## Commands

| Command | What it does |
|---|---|
| `composer run setup` | Full project setup (install, .env, key, migrate, npm, build) |
| `composer run dev` | Starts dev servers (artisan serve, queue, logs, Vite) concurrently |
| `composer run test` | Runs `config:clear` then PHPUnit via `php artisan test` |
| `npm run build` | Vite production build |
| `npm run dev` | Vite dev server |
| `php artisan make:model -m -c --resource --requests --policy` | Default model creation pattern |
| `php artisan make:migration` | Create migration |
| `php artisan make:test` | Create test |

Order: `lint (pint) -> typecheck (none) -> test`

## Architecture

- **No custom code exists yet** — app is the default Laravel skeleton with only the welcome route/view
- Project target: Inventory management system with RBAC (Admin/Staff), inbound/outbound tracking, multi-location stock (Gudang, Packing, Total), supplier/toko management
- See `.agents/prd.md` for full feature spec
- See `.agents/design.md` for UI/UX (LJN branding: orange `#FF6600`, blue `#0033CC`, JetBrains Mono + Inter)
- `app/Models/User.php` is the default — needs `role_id` FK to `roles` table for RBAC
- Existing migrations: users, cache, jobs tables only
- Routes in `routes/web.php` only (no API routes yet)
- `bootstrap/app.php` is the default — needs middleware aliases for admin role

## Conventions

- **No public registration** — accounts created via admin panel or DB only
- **No Pest** — PHPUnit for testing (Unit + Feature suites)
- **PHP attribute syntax preferred** over docblocks for Eloquent casts/fillables (see `User.php`)
- Form validation: quantity must be int > 0; outbound cannot exceed stock
- XSS via Blade `{{ }}` escaping; CSRF on all forms; rate limiting on login
- `.npmrc` has `ignore-scripts=true` — run `npm install --ignore-scripts` (already in setup)
- Indent: 4 spaces (PHP), 2 spaces (YAML)
- Linter: Laravel Pint (`./vendor/bin/pint`)

## Testing

```bash
composer run test                 # full test suite
php artisan test --filter=SomeTest  # single test
php artisan test tests/Unit/SomeTest.php
```

- Tests use in-memory SQLite (`phpunit.xml` config)
- No integration/end-to-end tests currently

## Key files

| Path | Purpose |
|---|---|
| `.agents/prd.md` | Product requirements — read before implementing features |
| `.agents/design.md` | Design tokens, typography, color palette — follow for all UI |
| `.env` | Active config (uses MySQL: `invenv2` DB) |
| `phpunit.xml` | Test config (in-memory SQLite) |
| `composer.json` | Scripts: `setup`, `dev`, `test` |
