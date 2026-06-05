# Scribble by Citrus / Citrus SMS Service

## Overview

Scribble by Citrus is a legacy Laravel-based SMS management platform. The application name used by the Laravel config is `Citrus SMS Service`, while the repository folder identifies the product as `Scribble by Citrus`.

The codebase is built for organizations that manage SMS clients, sender IDs, phone books, SMS campaigns, scheduled/recurring messages, invoices, support tickets, payment collection, and SMS gateway integrations from admin and client portals.

This is not a static site. It is a full-stack Laravel web application with Blade views, session-based admin/client authentication, a MySQL/MariaDB database, queued/scheduled background commands, and several third-party SMS/payment integrations.

## Key Features

- Client portal and admin portal with separate login routes.
- Client signup, verification, forgot-password, profile, avatar, and password-management flows.
- Admin dashboard for clients, administrators, roles, permissions, settings, localization, email templates, and language translation.
- SMS sending workflows for quick SMS, bulk SMS, file-based SMS, scheduled SMS, and recurring SMS.
- SMS campaign history, chat-style SMS reporting, inbox/reply handling, blocked-message review, spam-word filters, and blacklist contacts.
- SMS gateway management for HTTP and SMPP gateways, custom gateway callbacks, and two-way communication endpoints.
- Phone book, contact groups, CSV imports/exports, and downloadable sample CSV files.
- Sender ID management, SMS coverage/operators, SMS price plans, plan features, bundles, and keyword purchasing.
- Invoices, recurring invoices, PDF invoice download/print views, invoice payments, and SMS unit/plan purchases.
- Support ticket departments, ticket replies, and ticket file attachments.
- Public/API-like endpoints for SMS sending, contacts, coverage, two-way SMS replies, delivery reports, and WordPress-plugin style integrations.
- Scheduled console commands for queued bulk SMS, scheduled SMS, recurring SMS, recurring invoices, keyword validity checks, inbox checks, demo database refresh, and product status verification.

## Project Type

- Full-stack web app
- Laravel app
- Admin dashboard
- Client/customer portal
- SaaS-style SMS management platform

## Tech Stack

### Backend

- PHP application using Laravel `5.4.*` from `application/composer.json`.
- Laravel Blade templates under `application/resources/views`.
- Session authentication with separate `admin` and `client` guards in `application/config/auth.php`.
- Token guard for `/api/user` in `application/routes/api.php`.
- Eloquent-style models under `application/app/Models`, loaded through Composer classmap and the `App\` namespace.
- Custom application classes under `application/app/Classes` for permissions, phone numbers, PayPal/Paynow/TwoCheckout, SMPP, and SMS gateway helpers.

### Frontend

- Server-rendered Blade views.
- Frontend assets stored at the repository root under `assets/`.
- Laravel Mix `0.6` build config in `application/webpack.mix.js`.
- npm dev dependencies include Vue `^2.0.1`, jQuery, Bootstrap Sass, Axios, Lodash, webpack, and Laravel Mix.

### Database

- Default database driver is MySQL from `application/config/database.php`.
- PostgreSQL and SQLite connection templates are also present in Laravel config, but `.env.example` is set up for MySQL.
- Migrations live in `application/database/migrations`.
- Seeders live in `application/database/seeds`.
- SQL dump files exist in the working tree but are ignored by Git and should be treated as local/installer artifacts, not committed source.

### Tooling

- Composer for PHP dependencies.
- npm for frontend dependencies and Laravel Mix scripts.
- PHPUnit `~5.7` is declared in `require-dev`, and starter tests exist under `application/tests`.
- Laravel Artisan commands are used for key generation, migrations, seeders, cache tasks, scheduled jobs, and local framework operations.
- No Dockerfile, Docker Compose file, CI workflow, `composer.lock`, npm lock file, Yarn lock file, or pnpm lock file is present in this project copy.

## Project Structure

```txt
project-root/
|-- index.php                 # Root web entry point; loads application/bootstrap/app.php
|-- main.php                  # Laravel HTTP kernel bootstrap used by root index.php
|-- web.config                # IIS rewrite rules to route requests to index.php
|-- assets/                   # Public CSS, JS, images, library assets, and sample CSV files
|-- application/
|   |-- artisan               # Laravel Artisan CLI
|   |-- composer.json         # PHP dependency and autoload configuration
|   |-- package.json          # npm scripts and frontend dependencies
|   |-- webpack.mix.js        # Laravel Mix build pipeline
|   |-- app/
|   |   |-- Classes/          # Payment, SMS gateway, permission, SMPP, and utility classes
|   |   |-- Console/          # Scheduled Artisan commands
|   |   |-- Http/Controllers/ # Admin, client, SMS, invoice, payment, ticket controllers
|   |   |-- Mail/             # Mailables for auth, tickets, invoices, sender ID, SMS messages
|   |   |-- Models/           # Application data models
|   |-- bootstrap/            # Laravel bootstrap files and generated cache location
|   |-- config/               # Laravel, integration, mail, payment, SMS, and database config
|   |-- database/
|   |   |-- migrations/       # Schema migrations
|   |   |-- seeds/            # Initial data seeders
|   |-- install/              # Installer database artifact(s)
|   |-- packages/             # Local package code
|   |-- resources/views/      # Blade templates for admin, client, email, errors, installer
|   |-- routes/
|   |   |-- web.php           # Main web routes; 508 route declarations in this copy
|   |   |-- api.php           # Laravel API route skeleton
|   |-- storage/              # Runtime logs, sessions, cached views, app files
|   |-- tests/                # Starter Laravel feature/unit tests
|   |-- vendor/               # Composer dependencies; ignored by Git
|-- README.md                 # Project documentation
```

## Main Routes and URLs

The root web entry point is `index.php` at the repository root, not the standard Laravel `public/index.php` layout. A local web server should use the repository root as the document root.

Primary routes from `application/routes/web.php`:

- `/` - client login, or installer redirect when `APP_TYPE=new`.
- `/signup` - client registration.
- `/dashboard` - client dashboard after login.
- `/admin` - admin login.
- `/admin/dashboard` - admin dashboard after login.
- `/sms/api` - public SMS API endpoint handled by `PublicAccessController@ultimateSMSApi`.
- `/contacts/api` - contact subscription API endpoint.
- `/coverage/api` - coverage API endpoint.
- `/api/user` - token-authenticated Laravel API skeleton route from `application/routes/api.php`.

Example local URLs when the repository root is served at port `8000`:

- `http://127.0.0.1:8000/`
- `http://127.0.0.1:8000/admin`

## Prerequisites

- PHP compatible with Laravel 5.4 and the installed legacy packages. The project declares `php >=5.6.4`, but current local validation showed old vendor packages are not compatible with PHP 8.5. Use a PHP version below 8.0 unless the framework/dependencies are upgraded.
- Required PHP extensions from Composer/config checks include `curl`, `json`, `simplexml`, `mysqli`, `pdo`, `openssl`, `iconv`, `mbstring`, `gd`, `zip`, `xml`, and `tokenizer`.
- Composer.
- Node/npm compatible with the legacy Laravel Mix 0.6 toolchain. Current npm dry-run resolved old packages including `node-sass 4.14.1`, which is not suitable for modern Node 24 without remediation.
- MySQL or MariaDB for the default database setup.

## Environment Configuration

The safe template is `application/.env.example`. Copy it to `application/.env` for local development and replace placeholders with local values only.

```bash
cd application
cp .env.example .env
php artisan key:generate
```

Important environment groups:

- Application: `APP_NAME`, `APP_ENV`, `APP_STAGE`, `APP_TYPE`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `TIME_ZONE`, `TIMEOUT`, `REDIRECT_HTTPS`.
- Database: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- Drivers: `BROADCAST_DRIVER`, `CACHE_DRIVER`, `SESSION_DRIVER`, `SESSION_DOMAIN`, `SESSION_SECURE_COOKIE`.
- Mail: `MAIL_DRIVER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAILGUN_DOMAIN`, `MAILGUN_SECRET`, `SES_KEY`, `SES_SECRET`, `SPARKPOST_SECRET`.
- Cache services: `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT`, `MEMCACHED_*`.
- Object storage: `AWS_KEY`, `AWS_SECRET`, `AWS_REGION`, `AWS_BUCKET`.
- Broadcast services: `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`.
- Payment gateways: `STRIPE_*`, `PAYPAL_*`, `PAYSTACK_*`, `GOPAY_*`, `MERCHANT_EMAIL`.
- SMS/telephony: `TWILIO_*`, `AMI_*`, `SC_DEVICE`.

`APP_TYPE` matters: `AuthController@clientLogin` redirects to `install` when `APP_TYPE=new`. After a completed installation or manual setup, use the application-appropriate installed value expected by this legacy codebase.

Never commit `application/.env`.

## Installation

Install PHP dependencies from the Laravel app directory:

```bash
cd application
composer install
```

Install frontend dependencies from the same directory:

```bash
cd application
npm install
```

Known dependency caveats from validation:

- There is no `composer.lock`, so Composer resolves dependency versions fresh.
- Current Composer 2.9 blocks this dependency set because Laravel 5.4, PHPMailer 5.2, PHPUnit 5.7, and related packages have security advisories.
- There is no npm lock file, so npm also resolves dependencies fresh.
- On Node 24, the legacy frontend dependency tree did not complete a normal install in this environment.

## Database Setup

Configure the database connection in `application/.env`, then run migrations and seeders:

```bash
cd application
php artisan migrate --seed
```

Seeders included in `DatabaseSeeder`:

- `AppConfigSeeder`
- `ClientSeeder`
- `ClientGroupsSeeder`
- `AdminSeeder`
- `SMSGatewaysSeeder`
- `PaymentGatewaysSeeder`
- `EmailTemplateSeeder`
- `IntCountryCodesSeeder`
- `LanguageTableSeeder`
- `LanguageDataTableSeeder`

Security note: the legacy seeders include demo accounts and credentials. Review and replace seeded users, emails, passwords, API keys, and payment placeholders before using any shared, public, staging, or production environment.

## Running Locally

This project uses a non-standard public layout: root `index.php` loads the Laravel app from `application/` and binds the public path to the repository root.

Serve the repository root with Apache/IIS/Nginx/PHP built-in server. For quick local testing with a compatible PHP runtime:

```bash
php -S 127.0.0.1:8000 index.php
```

Then open:

- `http://127.0.0.1:8000/`
- `http://127.0.0.1:8000/admin`

Do not assume `php artisan serve` is the right local server for this copy. `application/server.php` references `application/public/main.php`, but `application/public/` is not present.

## Build Process

Available npm scripts from `application/package.json`:

```bash
cd application
npm run dev
npm run watch
npm run hot
npm run production
```

Current build caveat: `application/webpack.mix.js` references:

- `application/resources/assets/js/app.js`
- `application/resources/assets/sass/app.scss`

Those source files are not present in this project copy. Restore those sources or update the Mix config before expecting asset builds to pass.

## Test Process

The project declares PHPUnit `~5.7` in `application/composer.json` and includes starter tests:

- `application/tests/Feature/ExampleTest.php`
- `application/tests/Unit/ExampleTest.php`

Run tests after dependencies are installed and the app is on a compatible PHP runtime:

```bash
cd application
vendor/bin/phpunit
```

There is no `phpunit.xml` file in this project copy, so test behavior depends on PHPUnit defaults unless a config is restored.

## Scheduled Jobs

Custom Artisan commands are registered in `application/app/Console/Kernel.php` and scheduled as follows:

- `sms:schedule` - every minute.
- `sms:sendbulk` - every minute.
- `sms:sendrecurring` - every minute.
- `keyword:checkvalidity` - every minute.
- `invoice:recurring` - daily at `12:01`.
- `VerifyProductStatus:verify` - weekly.

Additional registered command:

- `powersms:checkinbox`
- `demo:updatedatabase`

Run Laravel's scheduler from cron or a process manager in compatible environments:

```bash
cd application
php artisan schedule:run
```

## Integrations

Integration evidence appears in `application/composer.json`, config files, routes, controllers, and app classes.

SMS/telephony integrations include:

- Twilio
- Nexmo
- Plivo
- Telesign
- Africa's Talking
- OVH
- SMPP
- AMI/Asterisk-related commands/config
- Custom HTTP/SMPP gateways
- Reply and delivery-report endpoints for providers such as Twilio, Textlocal, SMSGlobal, BulkSMS, Nexmo, Plivo, Infobip, MessageBird, Diafaan, WhatsApp, GatewayAPI, 46elks, SignalWire, APIWHA, Flowroute, Zang, SMPP, Thinq, Voyant, Telnyx, Bandwidth, and 019SMS.

Payment integrations include:

- PayPal
- Stripe
- Paystack
- GoPay
- Paynow
- Webxpay
- Moka
- CinetPay
- Slydepay
- 2Checkout
- OpenPayU / PayU-related code

Store real credentials only in environment variables or secured runtime configuration. Do not commit keys, tokens, gateway credentials, SMTP passwords, payment secrets, private URLs, or generated `.env` files.

## GitHub Safety Requirements

The root `.gitignore` is configured to ignore sensitive/generated artifacts, including:

- `application/.env` and other `.env.*` files except `.env.example`.
- `application/vendor/`.
- `application/node_modules/`.
- Laravel generated sessions, cached views, cache files, logs, and bootstrap cache files.
- SQL dumps such as `*.sql` and `*.sql.gz`.
- Local database files, archives, OS/editor files, certificates, and private keys.

Before committing:

```bash
git status --short
git check-ignore -v application/.env application/vendor/autoload.php application/node_modules/example
```

Before pushing, search for accidental secrets:

```bash
rg -n "api_key|apikey|secret|token|password|private_key|client_secret|access_token|bearer|sk-" .
```

Review matches carefully and mask or remove real values. Many legitimate code references contain words like `password` or `token`; do not commit actual credentials.

## Known Compatibility Notes

- Local validation with PHP 8.5 failed because Laravel 5.4 vendor code uses APIs deprecated/removed for modern PHP behavior.
- Composer 2.9 refused to resolve the declared dependency set because of security advisories in legacy packages.
- npm dependency installation did not complete in this environment on Node 24.
- The frontend Mix source paths are missing in this project copy.
- This README documents the actual commands and project structure, but a successful local run requires a compatible legacy PHP/Node toolchain or a deliberate dependency upgrade plan.

