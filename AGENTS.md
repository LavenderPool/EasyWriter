# AGENTS.md

## Cursor Cloud specific instructions

EasyWriter is a single monolithic **Laravel 13 (PHP 8.3)** app: server-rendered Blade admin panel + a Wattpad-style reader, with a Vite/Tailwind vanilla-JS frontend. Defaults to **SQLite** with database-backed session/cache/queue drivers, so no external DB/Redis/mail service is required for core flows or tests.

### Services & commands
- App server: `php artisan serve --host=0.0.0.0 --port=8000` (root `/` → redirects to `/admin/login`; reader at `/r/{token}`).
- All-in-one dev (server + queue + logs + Vite HMR): `composer dev`. Runs Vite on port 5173; not required if assets are already built with `npm run build`.
- Lint: `./vendor/bin/pint` (add `--test` to check without modifying).
- Tests: `composer test` (or `php artisan test`); uses in-memory SQLite, no external services needed.
- Frontend build: `npm run build`.

### Non-obvious caveats
- There is no public registration. Admins are created ONLY via `php artisan admin:create` (password min 12 chars). Create one before exercising admin flows.
- The SQLite file `database/database.sqlite` is not committed and must exist before `php artisan migrate`. The update script creates it if missing.
- `npm install` must use `--ignore-scripts` (also set in `.npmrc`).
- Manga page images are served through authorized routes off a private disk; `php artisan storage:link` must have been run (done in the update script) for served images to resolve.
- GeoIP country stats call `http://ip-api.com`; it degrades gracefully to "Unknown"/"Local" when outbound access is blocked, so it is not required for testing.
