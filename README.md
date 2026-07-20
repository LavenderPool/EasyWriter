# EasyWriter

Private manga publishing service (Wattpad-style reader) built with **Laravel 13** and **vanilla JS**.

## Features

- Admin panel for private mangas
- Page CRUD: **1 image = 1 page**
- Private publish via share links (Telegram-style multiple links per book)
- Per-link view counting and country stats (UI + JSON API)
- Secure admin auth (no public registration; admins only via console)
- Wattpad-like reader: page status, flip/swipe/keyboard navigation, progress in `localStorage`, responsive UI

## Requirements

- PHP 8.3+
- Composer
- SQLite (default) or MySQL/PostgreSQL
- GD/Imagick PHP extension for image uploads

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan storage:link
php artisan admin:create
php artisan serve
```

Open `http://localhost:8000/admin/login`.

### Create admin (only method)

```bash
php artisan admin:create
# or non-interactive:
php artisan admin:create --name="Admin" --email="admin@example.com" --password="your-secure-password"
```

Password minimum: **12 characters**.

## Usage

1. Create a manga in Admin → Mangas
2. Upload page images (multi-upload supported)
3. Enable **Publish privately**
4. Open **Share links**, create one or more links
5. Share a link like `/r/{token}`

### Country stats API

Authenticated admin endpoint:

```http
GET /admin/mangas/{manga}/links/{link}/countries
```

Returns JSON:

```json
{
  "link_id": 1,
  "label": "Telegram",
  "views_count": 12,
  "countries": [
    { "country_code": "DE", "country_name": "Germany", "views": 5 }
  ]
}
```

## Security notes

- No public registration
- Login rate-limited
- Session encryption enabled
- Manga images stored on private disk and served only through authorized routes
- Reader pages require an active share token
- Viewer IPs are stored hashed

## Reader controls

- Tap/click left/right zones, swipe, or arrow keys
- Space / PageDown → next page
- Slider + “Go to page”
- Progress restored from `localStorage`
- UI auto-hides; press `H` or tap center to toggle
