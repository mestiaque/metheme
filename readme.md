# MeTheme

A Laravel package that supports two domains in a single package - one for frontend and one for backend.

## Overview

This package allows you to use two separate domains (e.g., `xyz.com` for frontend and `admin.xyz.com` for backend) within a single Laravel application.

## Requirements

- Laravel 8.x or higher
- PHP 7.4 or higher

## Installation

### 1. Add Repository to composer.json

```json
"repositories": {
    "metheme": {
        "type": "vcs",
        "url": "https://github.com/mestiaque/metheme.git"
    }
}
```

### 2. Install the Package

```bash
composer require mestisque/metheme:dev-master
```

### 3. Publish Package Assets

```bash
php artisan vendor:publish --provider="Packages\Frontend\FrontendServiceProvider"
php artisan vendor:publish --provider="Packages\Backend\BackendServiceProvider"
```

### 4. Create Storage Link

```bash
php artisan storage:link --force
```

### 5. Configuration

#### Local Environment (.env)

```env
APP_URL=http://xyz.test
BACKEND_URL=http://admin.xyz.test
```

#### Production Environment (.env)

```env
APP_URL=https://estiaque.com
BACKEND_URL=https://admin.estiaque.com
```

## Service Providers

### FrontendServiceProvider

```php
<?php

namespace Packages\Frontend;

use Illuminate\Support\ServiceProvider;

class FrontendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (request()->getHost() == parse_url(config('app.url'), PHP_URL_HOST)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            $this->loadViewsFrom(__DIR__.'/../views', 'frontend');
        }
    }
}
```

### BackendServiceProvider

```php
<?php

namespace Packages\Backend;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (request()->getHost() == parse_url(config('backend.url'), PHP_URL_HOST)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            $this->loadViewsFrom(__DIR__.'/../views', 'backend');
        }
    }
}
```

## Mail Configuration (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.xyz.com
MAIL_PORT=465
MAIL_USERNAME=info@xyz.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@xyz.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Meta Configuration (.env)

```env
ME_META_TITLE="M. ESTIAQUE AHMED KHAN"
ME_META_AUTHOR="Estiaque"
ME_META_DESCRIPTION="Software Engineer from Dhaka, Bangladesh"
ME_META_KEYWORDS="laravel, php, developer"
```

## .env
```env
QUEUE_CONNECTION=database
```

## SMS Configuration (.env)
```env
SMS_API_URL=https://bulksmsbd.net/api/smsapi
SMS_API_KEY=dBG4rYOLWW28f3ip15yW
SMS_SENDER_ID=8809617624082
```

## Telegram Bot Configuration (.env)
```env
TELEGRAM_BOT_TOKEN=8355877001:AAHziYneb6prU4rrU7VK_LtMMspySGTha14
TELEGRAM_CHAT_ID=5543526664
TELEGRAM_WEBHOOK_SECRET=encodex__123
```
## Usage

After installation, the package will automatically:
- Load frontend routes when访问 the main APP_URL domain
- Load backend routes when访问 the BACKEND_URL domain
- Provide separate view namespaces for frontend and backend

## License

MIT License


## Cron Job
```
/usr/local/bin/php /home/xyz/public_html/artisan queue:work --stop-when-empty --sleep=3 --tries=3 --timeout=90 >> /home/xyz/public_html/storage/logs/worker.log 2>&1
```

## Telegram Webhook 
```
POST : https://xyz.com/telegram/webhook/{secrect_key} //set secrect key

POST: https://api.telegram.org/bot{api_token}/setWebhook?url=https://xyz.com/telegram/webhook/{secrect_key} // set api
 
GET : https://api.telegram.org/bot{api_token}/getWebhookInfo // get info
```
