# Scribble by Citrus

## Overview

Scribble by Citrus is a Laravel web application for SMS service operations, client account management, invoicing, payments, support, and a "Pro" visitor/transaction subscription module. The codebase includes separate admin, client, staff, admin-pro, pro-client, and API route areas, with Blade dashboards and controllers for SMS campaigns, contacts, invoices, receipts, payment callbacks, support tickets, staff roles, visitors, and Pro subscriptions.

The app is intended for operators/admins who manage SMS plans and gateways, clients who send and track SMS campaigns, staff users who handle assigned Pro workflows, and Pro clients who manage visitor and transaction records for subscribed businesses.

This README replaces the previous generic Laravel starter documentation. It is based on the project files in `app/`, `routes/`, `resources/views/`, `database/`, `composer.json`, `package.json`, `.env.example`, and `vite.config.js`.

## Key Features

- Multi-role authentication with separate session guards for admins, clients, and staff.
- Admin dashboard routes for clients, administrators, packages, accounts, plans, operators, SMS gateways, payment gateways, settings, localization, support departments, and reports.
- Client dashboard routes for registration, login, invoices, receipts, packages, accounts, contacts, user management, support tickets, SMS plans, keywords, and campaign reports.
- SMS tooling for quick SMS, bulk SMS, scheduled SMS, recurring SMS, templates, inbox, SMS history, campaign reports, sender ID management, spam words, blocked messages, two-way communication, and chat-style replies.
- Payment and invoice flows for invoices, SMS plans, SMS unit purchases, and keyword purchases.
- Payment gateway integrations referenced in code and views, including M-Pesa/Daraja, Stripe, Paystack, PayPal, GoPay, Slydepay, Webxpay, CinetPay, PayNow, Moka, PayU, Pagopar, Yandex Money, Alipay, and WeChat Pay.
- SMS provider integrations referenced in jobs, controllers, config, and views, including Twilio, Plivo, Africa's Talking, AWS SNS, TeleSign, Vonage, Safaricom/M-Pesa, and custom/SMPP gateways.
- Pro module for subscriptions, M-Pesa integration guidance, staff roles, transaction records, visitors, visitor businesses, and work history.
- PDF invoice/receipt generation through Laravel PDF tooling.
- CSV import/export flows for contacts and clients.
- Private file serving routes for client/admin profiles, staff images, visitor images, and sender ID files.

## Project Type

- Full-stack Laravel application
- Admin dashboard
- Client SMS SaaS platform
- Staff/pro operations portal
- Backend API endpoints for payment/SMS callbacks
- Blade-rendered web app with Vite-managed frontend assets

## Tech Stack

### Backend

- PHP with Laravel 9
- Laravel session authentication with custom guards: `admin`, `client`, `staff`, and token-based `api`
- Laravel Sanctum is installed
- Laravel Mail/Mailable classes for registration, password reset, invoices, receipts, support tickets, and sender ID workflows
- Queue jobs for bulk SMS, MMS, and voice sending
- PDF tooling: `barryvdh/laravel-dompdf`, `barryvdh/laravel-snappy`, `wkhtmltopdf`, `wkhtmltoimage`
- Data tables via Yajra DataTables
- Excel/CSV support via Maatwebsite Excel

### Frontend

- Blade templates in `resources/views`
- Vite with `laravel-vite-plugin`
- JavaScript entry files in `resources/js`
- CSS entry file in `resources/css`
- Public assets in `public/assets`

### Database

- MySQL is the default connection in `config/database.php` and `.env.example`.
- SQLite and PostgreSQL connection blocks exist in `config/database.php`, but the app is configured for MySQL by default.
- The model layer includes app configuration, admins, clients, invoices, receipts, payment gateways, SMS gateways, campaigns, support tickets, Pro subscriptions, staff, visitors, and visitor businesses.

### Tooling

- Composer for PHP dependencies
- npm for frontend dependencies
- Vite for frontend dev/build
- PHPUnit for tests
- Laravel Artisan for framework commands
- Laravel Pint is installed as a dev dependency, but no Composer script is defined for it

## Project Structure

```txt
project-root/
├── app/                    # Laravel application code
│   ├── Classes/            # Legacy/custom SMS and gateway helper classes
│   ├── Helpers/            # Global helper functions loaded by Composer
│   ├── Http/Controllers/   # Admin, client, staff, Pro, API, payment, SMS controllers
│   ├── Http/Middleware/    # Custom auth, staff, client, M-Pesa, CORS, timeout middleware
│   ├── Jobs/               # Bulk SMS, MMS, and voice jobs
│   ├── Mail/               # Mailable classes
│   ├── Models/             # Eloquent models
│   └── libraray/           # Legacy third-party gateway/payment libraries
├── bootstrap/              # Laravel bootstrap files and cache directory
├── config/                 # Laravel, payment, SMS, mail, DB, and service config
├── database/               # Migrations, seeders, factories
├── lang/                   # Laravel language files
├── packages/               # Local package code
├── public/                 # Web root and public assets
├── resources/
│   ├── css/                # Vite CSS entry
│   ├── js/                 # Vite JS/bootstrap entries
│   └── views/              # Blade layouts, dashboards, pages, emails, payment views
├── routes/                 # Route files for web, admin, staff, pro, admin-pro, API
├── storage/                # Runtime logs, cache, sessions, private uploaded files
├── tests/                  # PHPUnit unit and feature tests
├── composer.json           # PHP dependency and autoload config
├── package.json            # npm scripts and frontend dependencies
├── phpunit.xml             # PHPUnit configuration
├── vite.config.js          # Vite/Laravel plugin config
└── README.md               # Project documentation
```

## Route Areas and Local URLs

Routes are split by domain in `app/Providers/RouteServiceProvider.php`:

- Main/client/common web routes: `routes/web.php`
- Admin routes: `routes/admin.php`
- Staff routes: `routes/staff.php`
- Pro client routes: `routes/pro.php`
- Admin Pro routes: `routes/admin-pro.php`
- API callback routes: `routes/api.php`

With the default placeholders in `.env.example`, use:

```dotenv
APP_URL=http://localhost:8000
APP_DOMAIN=localhost
```

Typical local entry points:

- Main/client site: `http://localhost:8000`
- Admin domain routes: `http://admin.localhost:8000`
- Staff domain routes: `http://staff.localhost:8000`
- Pro client domain routes: `http://pro.localhost:8000`
- Admin Pro domain routes: `http://admin-pro.localhost:8000`
- API callback domain routes: `http://api.localhost:8000`

Depending on your OS/browser, you may need hosts-file entries for the subdomains:

```txt
127.0.0.1 admin.localhost
127.0.0.1 staff.localhost
127.0.0.1 pro.localhost
127.0.0.1 admin-pro.localhost
127.0.0.1 api.localhost
```

## Prerequisites

- PHP `>=8.0.2 <8.3`
- Composer 2
- Node.js and npm
- MySQL with the PDO MySQL PHP extension enabled
- A local database named to match `DB_DATABASE`

The current Composer lockfile is not compatible with PHP 8.5. Use PHP 8.0, 8.1, or 8.2 for normal install and runtime work.

## Installation

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

## Environment Configuration

Create a local environment file from the safe placeholder template:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

At minimum, configure the application URL/domain and local database:

```dotenv
APP_URL=http://localhost:8000
APP_DOMAIN=localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scribble
DB_USERNAME=root
DB_PASSWORD=
```

Do not put real production credentials into `.env.example`. Keep real credentials only in a local, ignored `.env`.

Important environment groups from `.env.example`:

- App/runtime: `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `APP_DOMAIN`, `TIME_ZONE`
- Database: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`
- Storage/AWS: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_REGION`
- Realtime/Pusher/Vite: `PUSHER_*`, `VITE_PUSHER_*`
- M-Pesa/Daraja: `MPESA_*`, `LIPA_NA_MPESA_PASS_KEY`
- Payments: `PAYPAL_*`, `PAYSTACK_*`, `STRIPE_*`, `GOPAY_*`
- SMS/telephony: `TWILIO_*`, `AMI_*`, `SC_DEVICE`

## Database Setup

Create a local MySQL database matching `DB_DATABASE`.

Run migrations:

```bash
php artisan migrate
```

The `database/migrations` folder currently contains migrations for Pro plans/subscriptions, short code transactions, OTPs, staff, staff roles, sessions, Pro SMS-not-sent records, visitor businesses, and visitors.

This legacy app also references many tables through models and helpers, including `sys_app_config`, clients, invoices, receipts, SMS gateways, SMS history, support tickets, payment gateways, contacts, campaigns, and language data. Those tables are not all represented by the current migration set. A full local run may require a sanitized database snapshot or additional migrations for the legacy schema. SQL dumps may exist locally, but they are ignored by `.gitignore` and should not be committed if they contain real data or secrets.

## Running Locally

Start the Laravel development server:

```bash
php artisan serve
```

Start the Vite development server:

```bash
npm run dev
```

Open `http://localhost:8000` or one of the subdomain URLs listed above.

## Build

The frontend build script defined in `package.json` is:

```bash
npm run build
```

On Windows, if this project is stored in a path containing special shell characters such as `&`, the npm `.cmd` shim may fail to resolve Vite. In that case, run Vite directly:

```powershell
node .\node_modules\vite\bin\vite.js build
```

## Tests and Quality Checks

PHPUnit is configured in `phpunit.xml` with Unit and Feature test suites:

```bash
php artisan test
```

Useful framework checks:

```bash
php artisan route:list
php artisan config:clear
php artisan package:discover
composer validate --no-check-publish
composer audit
npm audit --audit-level=high
```

Notes:

- Feature tests hit the application homepage, which uses database-backed app configuration. Ensure MySQL and `pdo_mysql` are available and the required schema/data exists.
- `npm audit` currently reports advisories in the Vite/esbuild chain. The suggested automatic fix requires a major Vite upgrade, so do not run `npm audit fix --force` without testing compatibility.
- `composer audit` reports remaining advisories and abandoned packages in this legacy dependency set. Major framework/package upgrades should be planned and tested separately.

## Available Scripts and Commands

From `package.json`:

```bash
npm run dev
npm run build
```

From Laravel/Artisan:

```bash
php artisan serve
php artisan migrate
php artisan test
php artisan route:list
php artisan config:clear
php artisan package:discover
php artisan key:generate
```

Composer lifecycle scripts are defined for package discovery, Laravel asset publishing after updates, creating `.env` after root package install, and generating an app key after project creation.

## Authentication and Roles

Authentication is configured in `config/auth.php` with:

- `admin` session guard
- `client` session guard
- `staff` session guard
- `api` token guard using the client provider

Custom route middleware in `app/Http/Kernel.php` protects admin, client, staff, Pro, M-Pesa, CORS, session timeout, and subscription-specific flows.

## Integrations

The codebase references multiple integrations through Composer packages, config files, controllers, jobs, routes, and views:

- SMS/voice providers: Twilio, Plivo, Africa's Talking, AWS SNS, TeleSign, Vonage, SMPP/custom gateways
- Payment providers: M-Pesa/Daraja, Stripe, Paystack, PayPal, GoPay, Slydepay, Webxpay, CinetPay, PayNow, Moka, PayU, Pagopar, Yandex Money, Alipay, WeChat Pay
- Mail providers/config: SMTP, Mailgun, SES, SparkPost
- Realtime/broadcasting config: Pusher
- File/storage config: local storage and AWS/S3-compatible configuration

Only use sandbox or local test credentials during development.

## GitHub Safety

The project is configured to ignore:

- `.env` and `.env.*`, while allowing `.env.example`
- `vendor/` and `node_modules/`
- Laravel cache, logs, sessions, runtime storage, and build output
- SQL dumps, zip archives, logs, and temporary output files
- IDE/OS metadata

Before committing or pushing:

```bash
git status
git ls-files
composer audit
npm audit --audit-level=high
```

Never commit real credentials, private database dumps, production logs, uploaded private files, or generated dependency folders. Any credentials that were previously exposed in local files should be rotated before pushing to GitHub.

## Known Local Caveats

- This folder must be initialized as a Git repository before `git status` and `git ls-files` work.
- Use PHP 8.0 through 8.2 for normal dependency installation; the lockfile is not compatible with PHP 8.5.
- A complete local run needs MySQL PDO support and a database schema/data set beyond the limited migrations currently present.
- The legacy installer package remains installed, but its service provider is disabled in `config/app.php` because this app already has `storage/installed` and the package caused route inspection conflicts.
- Some legacy helper and library namespaces do not follow PSR-4 conventions; Composer may print autoload warnings.

## Documentation Update Summary

This README now documents the actual application surface found in the codebase: the multi-domain Laravel route structure, role-specific dashboards, SMS and payment features, Pro subscription/staff/visitor modules, exact Composer/npm scripts, required environment groups, database setup limitations, build/test commands, and GitHub safety rules.

The documented commands match `composer.json`, `package.json`, `phpunit.xml`, `vite.config.js`, and Laravel Artisan commands available in this project.
