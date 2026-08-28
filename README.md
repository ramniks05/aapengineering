# AAP Engineerings

PHP (Laravel 12) website for **AAP Engineerings** — complete electrical projects, public portfolio with filters, enquiry form, and admin panel.

## Features

- Public pages: Home, About, Services, Projects (filter by status / city / search), Project detail, Enquiry
- Project statuses: Upcoming, Ongoing, Completed
- Media on project detail:
  - Images via **CDN URL**
  - Videos via **CDN URL** or **YouTube**
- Admin: login, projects CRUD, media management, cities, enquiries

## Requirements

- PHP 8.2+
- Composer
- MySQL/MariaDB (production) or SQLite (local demo)

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
```

### Database

**Local (SQLite already configured):**

```bash
php artisan migrate --seed
```

**Hosting (MySQL):** edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

Then:

```bash
php artisan migrate --seed
```

### Run locally

```bash
php artisan serve
```

- Website: http://127.0.0.1:8000  
- Admin: http://127.0.0.1:8000/admin/login  

**Default admin (change after first login):**  
- Email: `admin@aapengineerings.com`  
- Password: `Admin@123`

## Deploy on PHP hosting

1. Upload project files (or deploy via Git).
2. Point the domain document root to the `public/` folder.
3. Set `.env` with production `APP_URL`, `APP_DEBUG=false`, and MySQL credentials.
4. Run `composer install --no-dev --optimize-autoloader` and `php artisan migrate --seed` (or migrate only on live).
5. Ensure `storage/` and `bootstrap/cache/` are writable.

### Hostinger (Node build error fix)

If deploy fails with **`ERROR: No output directory found after build`**:

Vite builds into `public/build`, but Hostinger looks for a root folder like `dist`.  
This project now runs:

```bash
npm run build
```

which creates both `public/build` and `dist`.

In Hostinger deploy / Node settings use:

| Setting | Value |
|--------|--------|
| Build command | `npm run build` |
| Output directory | `dist` |
| PHP document root | `public` |

**Recommended for this site:** if Hostinger allows turning off the frontend/Node build, turn it off. The live site already uses `public/css/app.css` and does not require Vite assets.

## Contact / WhatsApp / Map

Dummy demo values are already set in `config/company.php`. Override in `.env` when ready:

```env
COMPANY_PHONE="+91 98765 43210"
COMPANY_EMAIL=info@aapengineerings.com
COMPANY_SUPPORT_EMAIL=projects@aapengineerings.com
COMPANY_ADDRESS="Office No. 204, Green Tech Plaza, Hinjewadi Phase 1, Pune, Maharashtra 411057"
COMPANY_HOURS="Mon – Sat: 9:30 AM – 6:30 PM"
COMPANY_WHATSAPP=919876543210
COMPANY_MAP_EMBED_URL="https://maps.google.com/maps?q=Hinjewadi%20Phase%201%2C%20Pune&output=embed"
COMPANY_MAP_LINK="https://www.google.com/maps/search/?api=1&query=Hinjewadi+Phase+1,+Pune"
```

`COMPANY_WHATSAPP` should be country code + number with no `+` or spaces (example: `919876543210`).


| Type | Field |
|------|--------|
| Image (CDN) | Full CDN image URL |
| Video (CDN) | Full CDN `.mp4` (or similar) URL + optional thumbnail URL |
| Video (YouTube) | Full YouTube watch/share URL or video ID |

Cover image is a separate CDN URL on the project form (also auto-filled from the first image media if empty).
