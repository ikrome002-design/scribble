# Scribble by Citrus

## Overview

Scribble by Citrus is a legacy Laravel-based SMS service platform. The application exposes separate client and administrator portals for managing SMS sending, contacts, SMS gateways, sender IDs, pricing plans, invoices, support tickets, keywords, recurring SMS, and campaign reporting.

The Laravel application lives in `application/`. The project root also contains the deployed entry point (`index.php`), public asset folders (`assets/`, `installer/`), and IIS rewrite configuration (`web.config`). The default Laravel app name in `application/.env.example` is `Citrus SMS Service`.

## Key Features

- Client and administrator login, registration, password reset, profile, and dashboard flows.
- Admin management for clients, administrators, roles, permissions, settings, localization, languages, and email templates.
- Contact lists, phone books, CSV import/export helpers, blacklist contacts, spam words, and block-message review.
- SMS sending flows for quick SMS, bulk SMS, CSV/file-based SMS, scheduled SMS, recurring SMS, templates, inbox/history, chat-style replies, and campaign reports.
- SMS gateway management for HTTP gateways, custom gateways, SMPP gateways, sender IDs, delivery reports, and two-way message callbacks.
- Public API routes for SMS, contacts, and coverage.
- Invoices, recurring invoices, PDF/download/print views, payment callbacks, SMS plan purchases, unit purchases, and keyword purchases.
- Support ticket departments, tickets, replies, and ticket file downloads.
- Background Artisan commands for scheduled SMS, file-based bulk SMS, recurring SMS, recurring invoices, keyword validity checks, inbox polling, demo data updates, and product status verification.

## Project Type

- Full-stack Laravel web application
- Admin dashboard
- Client portal
- SMS SaaS/platform application
- Server-rendered Blade UI with legacy frontend assets

## Tech Stack

### Backend

- PHP application built on Laravel 5.4 (`application/composer.json`)
- Laravel session guards for `admin` and `client`
- Laravel token guard configured for API access
- Eloquent models under `application/app/Models`
- Artisan console commands under `application/app/Console/Commands`

### Frontend

- Blade templates under `application/resources/views`
- Static runtime assets under root `assets/`
- Laravel Mix 0.6 and Webpack 2 build config in `application/webpack.mix.js`
- npm dev dependencies include Vue 2, jQuery, Bootstrap Sass, Axios, Lodash, Cross Env, and Laravel Mix

### Database

- Default database connection: MySQL
- Laravel config also includes SQLite and PostgreSQL connection definitions
- Migrations and seeders live in `application/database`
- Installer SQL dumps are present in `installer/database/ultimate-sms.sql` and `application/install/database/ultimate-sms.sql`

### Tooling

- Composer for PHP dependencies
- npm for frontend dependencies and asset builds
- Laravel Artisan for migrations, seeders, scheduled jobs, and local serving
- PHPUnit is listed in `require-dev`, and example test files exist, but no root PHPUnit config file was found

## Project Structure

```txt
project-root/
|-- index.php                       # Root web entry point; boots application/bootstrap/app.php
|-- main.php                        # Environment and writable-directory installer/preflight checks
|-- web.config                      # IIS rewrite/server config
|-- robots.txt
|-- assets/                         # Runtime CSS, JS, images, libraries, uploaded/sample assets
|-- installer/                      # Installer assets and installer SQL dump
|-- application/
|   |-- artisan                     # Laravel CLI entry
|   |-- composer.json               # PHP dependencies and Composer scripts
|   |-- package.json                # npm scripts and frontend build dependencies
|   |-- webpack.mix.js              # Laravel Mix build entry
|   |-- app/
|   |   |-- Console/Commands/       # Scheduled/background commands
|   |   |-- Helpers/                # Application helper functions
|   |   |-- Http/Controllers/       # Admin, client, SMS, payment, support, reports controllers
|   |   |-- Http/Middleware/        # Admin/client auth, demo, CORS, timeout middleware
|   |   |-- Jobs/                   # Bulk SMS, MMS, and voice jobs
|   |   |-- Models/                 # Eloquent models
|   |   |-- Classes/                # SMS/payment/helper classes
|   |   `-- libraray/               # Bundled third-party gateway/payment SDK code
|   |-- config/                     # Laravel, database, auth, mail, service, payment configs
|   |-- database/
|   |   |-- migrations/             # Schema migrations
|   |   `-- seeds/                  # Seed data
|   |-- install/                    # Application installer database dump
|   |-- public/                     # Generated Mix assets; ignored by Git
|   |-- resources/
|   |   |-- assets/js/app.js        # Minimal Mix entry point
|   |   |-- lang/                   # Translations
|   |   `-- views/                  # Blade views
|   |-- routes/
|   |   |-- web.php                 # Main web, callback, and public API routes
|   |   |-- api.php                 # Default auth:api user route
|   |   `-- console.php             # Console route bootstrap
|   |-- storage/                    # Laravel storage, cache, logs, uploads
|   `-- tests/                      # Laravel example Feature/Unit test skeletons
`-- README.md
```

## Main Application URLs

These routes are defined in `application/routes/web.php`:

- `/` - client login
- `/signup` - client registration
- `/admin` - administrator login
- `/dashboard` - client dashboard
- `/admin/dashboard` - administrator dashboard
- `/sms/api` - public SMS API endpoint
- `/contacts/api` - public contacts API endpoint
- `/coverage/api` - public coverage API endpoint

Local development with `php artisan serve` normally uses `http://localhost:8000`.

## Requirements

- PHP 7.x is recommended for this codebase. The current installed dependencies are not compatible with PHP 8.5.
- PHP extensions used or checked by the project include `curl`, `json`, `simplexml`, `mysqli`, `pdo`, `openssl`, `iconv`, `mbstring`, `gd`, and `zip`.
- Composer 2.x
- Node.js and npm
- MySQL or MariaDB for the default database path

The root `main.php` preflight checks also require writable Laravel storage/cache paths and enabled PHP functions such as `proc_open`, `curl_version`, and `base64_decode`.

## Environment Configuration

Create a local environment file from the safe placeholder template:

```powershell
cd application
copy .env.example .env
```

Do not commit `.env`. The template includes placeholders for:

- Application settings: `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL`, `APP_STAGE`, logging, timezone
- Database: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Cache/session/queue-related drivers
- Mail providers: SMTP, Mailgun, SES, SparkPost
- Payment providers: Stripe, PayPal, Paystack, GoPay
- Messaging/broadcasting providers: Twilio, AWS, Pusher
- Asterisk/AMI settings

Generate a real local app key only in your private `.env`:

```powershell
php artisan key:generate
```

## Installation

From the Laravel app directory:

```powershell
cd application
composer install
npm ci --ignore-scripts
```

Important Composer note: this source package does not include `composer.lock`. On modern Composer, `composer install` resolves dependencies like an update and may be blocked by security advisories for the legacy Laravel 5.4 dependency set. Regenerate and review a lock file on a compatible PHP 7.x environment before using this in production.

## Database Setup

Configure the database values in `application/.env`, then use one of the project-supported setup paths:

```powershell
php artisan migrate
php artisan db:seed
```

The seeders load application config, demo admin/client data, SMS gateways, payment gateway placeholders, email templates, country codes, and language data. Change any seeded demo accounts immediately in a private local database.

Alternatively, importer SQL dumps exist at:

- `installer/database/ultimate-sms.sql`
- `application/install/database/ultimate-sms.sql`

Use only one database setup path for a given local database unless you know the schema state.

## Running Locally

Start the Laravel development server from `application/`:

```powershell
php artisan serve
```

Then open:

- `http://localhost:8000/`
- `http://localhost:8000/admin`

The current machine validation showed PHP 8.5 cannot run Artisan for this project because Laravel 5.4/Symfony dependencies trigger fatal deprecation errors. Use a PHP 7.x runtime for backend commands.

## Frontend Build

Available npm scripts from `application/package.json`:

```powershell
npm run dev
npm run watch
npm run hot
npm run production
```

The build compiles `application/resources/assets/js/app.js` into `application/public/js/app.js`. Most UI assets used by the legacy templates are stored directly under the root `assets/` directory.

Validated locally:

```powershell
npm ci --ignore-scripts
npm run production
```

## Background Jobs and Scheduler

Registered Artisan command signatures include:

- `sms:schedule`
- `sms:sendbulk`
- `sms:sendrecurring`
- `keyword:checkvalidity`
- `invoice:recurring`
- `VerifyProductStatus:verify`
- `demo:updatedatabase`
- `powersms:checkinbox`

The scheduler in `application/app/Console/Kernel.php` runs SMS and keyword jobs every minute, recurring invoices daily at `12:01`, and product status verification weekly.

On a compatible PHP runtime, Laravel’s scheduler is normally invoked with:

```powershell
php artisan schedule:run
```

## Tests

The project contains Laravel example test files in:

- `application/tests/Feature/ExampleTest.php`
- `application/tests/Unit/ExampleTest.php`

However, no `phpunit.xml` or `phpunit.xml.dist` file was found at `application/`, and the current PHP 8.5 runtime cannot execute Artisan for this dependency set. A verified test command is therefore not documented here.

## Integrations

The codebase includes configuration, packages, routes, or classes for these integration areas:

- SMS/messaging: Twilio, Nexmo, Plivo, Telesign, AfricasTalking, OVH, SMPP, GoIP, BSG, SMSGateway.me, PowerSMS inbox polling, and many provider callback routes.
- Payments: Stripe, PayPal, Paystack, GoPay, PayNow, Slydepay, Webxpay, Moka, CinetPay, 2Checkout, PayU, Pagopar, Alipay-related SDK code.
- Email/services: SMTP, Mailgun, SES, SparkPost, PHPMailer.
- Infrastructure/service helpers: AWS SDK, Pusher broadcasting config, Redis, Memcached, Laravel queues.

All provider credentials must be supplied through private environment values, database settings, or admin settings. Do not commit real credentials.

## GitHub Safety Notes

- `.env` and `.env.*` are ignored.
- `application/.env.example` is safe to commit because it contains placeholders only.
- `application/vendor/`, `application/node_modules/`, and generated Mix assets are ignored.
- Real payment/API/SMS/email credentials must never be committed.
- Any real credentials previously stored in old copies of this project should be rotated.

## Known Maintenance Risks

- Laravel 5.4 and several bundled dependencies are legacy and have known security advisories.
- No `composer.lock` file is present, so fresh Composer installs may resolve differently from the original environment.
- PHP 8.x is not currently compatible with the installed backend dependency set.
- Some third-party SDK code is bundled directly inside `application/app/libraray/`.
- The test setup is incomplete until a PHPUnit config and compatible runtime are restored.

## Validation Performed

The README commands and claims were checked against:

- `application/composer.json`
- `application/package.json`
- `application/webpack.mix.js`
- `application/.env.example`
- `application/config/*.php`
- `application/routes/web.php`
- `application/app/Console/Kernel.php`
- `application/app/Console/Commands`
- `application/database/migrations`
- `application/database/seeds`
- `application/resources/views`
- root `index.php`, `main.php`, `.gitignore`, and `web.config`

Locally confirmed:

- `npm ci --ignore-scripts` passed.
- `npm run production` passed.
- `composer validate --no-check-publish` passed with a warning about an exact Symfony version constraint.
- `composer install --dry-run` failed because no `composer.lock` exists and modern Composer blocks vulnerable legacy package versions.
- `php artisan --version` failed on PHP 8.5 due Laravel/Symfony compatibility errors.
