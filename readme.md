# MeTheme

A Laravel admin package by [M. Estiaque](https://mestiaque.com) — ships an authentication flow (login, registration, forgot-password with OTP), role/permission-based sidebar navigation, activity logging, a searchable admin header, settings management, and a glassmorphism-styled UI theme, ready to drop into a standard Laravel application.

## Requirements

- PHP ^8.2
- Laravel ^12.0 or ^13.0
- The `zip` PHP extension

## Installation

```bash
composer require mestiaque/metheme
```

The service provider (`ME\MEServiceProvider`) is auto-discovered by Laravel — no manual registration needed. On install it automatically:

- Registers the package's routes (`web`, `api`, `auth`, `file`)
- Loads its migrations
- Loads its views under the `me::` namespace
- Loads its translations
- Registers the `authorization`, `activityLog` / `activity.logger` middleware aliases
- Merges its default config (`sidebar`, `permissions`, `auth`, `me_settings`) so the package works even before you publish anything

## Publishing assets

Publish whichever pieces you need to customize:

```bash
# Public assets (JS/CSS/images used by the theme)
php artisan vendor:publish --tag=metheme-assets

# Auth config overrides
php artisan vendor:publish --tag=metheme-auth-config

# Error pages (403/404/419/429/500/503) so you can customize them
php artisan vendor:publish --tag=metheme-errors
```

Add `--force` to re-publish and overwrite existing files.

## Post-install steps

```bash
php artisan migrate
php artisan storage:link
```

Your app's base `App\Http\Controllers\Controller` class is expected to exist (the default in any standard Laravel install) — several of the package's controllers extend it.

## License

MIT
