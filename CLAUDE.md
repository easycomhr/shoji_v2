# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Backend
php artisan serve           # Start dev server
php artisan migrate         # Run migrations
php artisan migrate:fresh --seed  # Reset DB with seed data
php artisan tinker          # Interactive REPL

# Frontend
npm run dev                 # Vite dev server (hot reload)
npm run build               # Production bundle

# Code quality
./vendor/bin/pint           # Laravel Pint (PHP code formatter/linter)

# Tests
./vendor/bin/phpunit                    # All tests
./vendor/bin/phpunit tests/Unit         # Unit only
./vendor/bin/phpunit tests/Feature      # Feature only
./vendor/bin/phpunit --filter TestName  # Single test
```

## Architecture

**Laravel 10 HRM system** (HR, payroll, timekeeping). Server-rendered Blade + Vite. Two MySQL databases: `shoji_v2` (current, port 3307) and legacy `shoji` (port 3308) for migration sync.

### Layer Stack

```
Routes (routes/web.php) → Middleware → Controller → Repository → Model
                                     ↘ Service (complex business logic)
```

- **Controllers** (`app/Http/Controllers/Admin/`): 37+ controllers, thin — delegate to repositories/services.
- **Repositories** (`app/Repositories/`): All DB queries go through `BaseRepository`. Never query Eloquent directly in controllers.
- **Services** (`app/Services/`): Complex logic lives here — `PayrollService`, `SyncDataService`, `CalculateSalaryService`, etc.
- **Models** (`app/Models/`): 45+ Eloquent models. `User` uses SoftDeletes + HasApiTokens (Sanctum).
- **Traits** (`app/Traits/`): `SalaryCalculationTrait`, `TemporalDataTrait` for shared model behavior.

### Key Architectural Decisions

1. **Dual-DB sync**: `SyncDataService` migrates data from legacy `shoji` DB to `shoji_v2`. The old DB connection is `DB_OLD_*` in `.env`.
2. **Repository pattern**: `BaseRepository` provides fluent `where/with/take/get/first/create/update/delete`. All 28 repositories extend it.
3. **Admin prefix**: All authenticated routes live under the `admin` prefix with `auth` middleware. Public routes (login, home shop) are at root.
4. **ExtJS grids**: Advanced data tables use ExtJS 7 from `public/assets/plugins/extjs/` — not Blade components.
5. **Global helper**: `app/Helpers/Helper.php` is auto-loaded via Composer for utility functions.
6. **Form validation**: Use `app/Http/Requests/` classes, not inline controller validation.

### Adding a New Feature

Follow the existing pattern: create Migration → Model → Repository → Service (if needed) → Controller → Request → Blade view → register route in `routes/web.php` under the admin group.

### Key Config Files

| File | Purpose |
|------|---------|
| `config/constants.php` | App-wide enums and constants |
| `config/company.php` | HRM company-specific settings |
| `config/menus.php` | Admin nav structure |
| `config/messages.php` | User notification messages |

### Employee Filter Combobox Convention

All screens with an employee filter (search by user/employee) MUST use:

- **Store:** `Ext.data.Store` loaded from `MASTER_ROUTE.USERS_URL`
- **Component:** `xtype: 'combobox'`, `queryMode: 'local'`, `typeAhead: true`
- **displayField:** `'custom_name'` (format: "CODE - Full Name")
- **valueField:** `'id'` (integer `users.id`)
- **Backend param:** send as `user_id` (NOT `code`, NOT `userid`)
- **Width:** 220px minimum
- Add `select` listener to trigger `mainStore.load()` on selection
- Add `change` listener to reload on clear (empty value)

Reference implementation: `public/js/admin/labour_contract/index.js`
