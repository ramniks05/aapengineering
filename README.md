# AAP Engineerings

Plain **PHP** website for **AAP Engineerings** — complete electrical projects, public portfolio with filters, enquiry form, and admin panel.

No Laravel, no Composer, no Node build — works on Hostinger shared hosting.

## Features

- Public pages: Home, About, Services, Projects (filter by status / city / search), Project detail, Clients, Gallery, Updates, Contact, Enquiry
- Project statuses: Upcoming, Ongoing, Completed
- Media: CDN images, CDN videos, YouTube embeds
- WhatsApp floating button + contact map
- Admin at `/manage/login` (projects, clients, gallery, updates, cities, enquiries)

## Requirements

- PHP 8.1+ with PDO MySQL
- MySQL / MariaDB

## Quick setup (local or Hostinger)

1. Copy `config.example.php` → `config.php` and set database + site URL.
2. Import `database.sql` in phpMyAdmin **or** open `install.php?key=AapSetup2026` once in the browser.
3. Delete `install.php` after setup.
4. Point the domain document root to the project folder (where `index.php` lives).

### Local (XAMPP)

```bash
# Copy config
copy config.example.php config.php
# Edit config.php: app_url = http://localhost/aapEngineering
# Import database.sql in phpMyAdmin, then visit the site
```

Or use PHP built-in server from the project folder:

```bash
php -S 127.0.0.1:8000
```

Set `app_url` in `config.php` to `http://127.0.0.1:8000`.

### Default admin

- URL: `/manage/login`
- Email: `admin@aapengineerings.com`
- Password: `Admin@123`

Change the password after first login (update the `users` table hash in phpMyAdmin, or add a password-change feature later).

## Deploy on Hostinger

| Setting | Value |
|--------|--------|
| Framework | PHP |
| Build command | *(empty)* |
| Output directory | *(empty)* |
| Composer install | **Off** |
| Document root | `public_html` (project root with `index.php`) |

Steps:

1. Deploy / upload all files to `public_html`.
2. Create `config.php` from `config.example.php` with Hostinger MySQL credentials.
3. Run `https://yoursite.com/install.php?key=AapSetup2026` once, or import `database.sql` in phpMyAdmin.
4. Delete `install.php`.
5. Visit `https://yoursite.com/manage/login`.

## Project structure

```
index.php          — front router
.htaccess          — clean URLs
config.example.php — copy to config.php (not in git)
database.sql       — schema + demo data
install.php        — one-time browser setup
assets/css/        — styles
includes/          — bootstrap, DB, helpers, layouts
pages/             — public pages
manage/            — admin panel
```

## Company contact settings

Edit the `company` section in `config.php` (phone, email, WhatsApp, map embed, hours).

## Media in admin

| Type | Field |
|------|--------|
| Image (CDN) | Full CDN image URL |
| Video (CDN) | Full CDN video URL + optional thumbnail |
| Video (YouTube) | YouTube watch URL or video ID |

Cover image is a separate CDN URL on the project form.
