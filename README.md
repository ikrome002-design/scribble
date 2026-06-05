# Scribble by Citrus

## Overview

Scribble by Citrus is a Laravel web application for SMS service management, client billing, subscriptions, staff/team operations, and payment collection. The codebase includes public marketing pages, client registration/login flows, admin and staff dashboards, SMS campaign/contact tooling, invoicing and receipts, Pro subscription workflows, visitor/transaction tracking, and integrations for SMS and payment providers.

The primary users are business clients who manage SMS communication and billing, internal admins who configure plans, gateways, clients, and reports, staff users who work with visitor/transaction records, and team/pro users with subscription-specific access.

## Key Features

- Public website pages for home, pricing, solutions, FAQ, contact, and legal content.
- Client registration, login, verification, password reset, profile, and dashboard flows.
- Separate admin, staff, pro, team, admin-pro, and team-pro route areas.
- Custom session authentication guards for clients, admins, and staff.
- SMS contacts, contact groups, sender IDs, templates, gateways, scheduled SMS, recurring SMS, bulk SMS, inbox/history, blacklists, and spam word models.
- Invoice, mass invoice, recurring invoice, receipt, PDF receipt/invoice, and payment flows.
- Payment integration code/configuration for PayPal, Paystack, Stripe, GoPay, M-Pesa/Daraja, CoinPayments, Slydepay, and manual payments.
- SMS/communication integration packages or configuration for Africa's Talking, Twilio, Plivo, Vonage, Telesign, OSMS, and custom SMS gateways.
- Pro subscription modules, Pro plans, Pro subscription files, staff role assignment, visitor records, visitor businesses, and transaction records.
- Team subscription, team plans, team members, and team member action tracking.
- Support ticket, support department, support reply, testimonial, language, and app configuration models.
- Scheduled Artisan commands for SMS delivery, recurring invoices, keyword validity, OTP cleanup, and Pro subscription maintenance.

## Project Type

- Full-stack web app
- Laravel app
- SaaS/admin dashboard style application
- Server-rendered Blade application with Vite-managed frontend assets

## Tech Stack

### Backend

- PHP, required by `composer.json` as `^8.0.2`.
- Laravel Framework `^9.19`.
- Laravel Sanctum package and `personal_access_tokens` migration.
- Laravel session guards for `client`, `admin`, and `staff`.
- Laravel queues, scheduler, mail, storage, and Blade views.
- PDF/export and reporting packages including DomPDF, Snappy, Maatwebsite Excel, Laravel DataTables, and Chart.js integration.

### Frontend

- Blade templates under `resources/views`.
- Vite 4 with `laravel-vite-plugin`.
- JavaScript entry point at `resources/js/app.js`.
- Axios and Lodash loaded through `resources/js/bootstrap.js`.
- Static public assets under `public/assets`.

### Database

- MySQL is the default database connection in `config/database.php` and `.env.example`.
- SQLite and PostgreSQL connection blocks exist in `config/database.php`, but the project defaults to MySQL.
- Redis configuration exists for cache/queue-adjacent Laravel services.
- Migrations currently cover Sanctum tokens, Pro plans/subscriptions, short code transactions, staff, staff roles, OTPs, sessions, Pro SMS not-sent records, visitor businesses, and visitors.

### Tooling

- Composer for PHP dependencies.
- npm for frontend dependencies.
- Vite for frontend development/build output.
- PHPUnit via `phpunit.xml`.
- Laravel Pint is listed in `require-dev`, but no custom Pint config file was found.
- No Dockerfile, Docker Compose file, or GitHub Actions workflow was found in this snapshot.

## Project Structure

```txt
project-root/
├── app/                 # Laravel application logic, models, controllers, middleware, console commands
├── app/Http/Controllers # Client, admin, staff, team, pro, payment, SMS, invoice, and API controllers
├── app/Models           # Eloquent models for clients, SMS, invoices, plans, staff, teams, visitors, tickets
├── bootstrap/           # Laravel bootstrap files and generated cache directory
├── config/              # Laravel and integration configuration files
├── database/            # Migrations, factories, and seeders
├── lang/                # Laravel language files
├── packages/            # Local package code, including licenseChecker
├── public/              # Public entry point, assets, installer assets, and generated build output
├── resources/css        # Vite CSS entry point
├── resources/js         # Vite JavaScript entry points
├── resources/views      # Blade views for public, client, admin, staff, team, pro, payments, emails
├── routes/              # Web, API, admin, staff, team, pro, and admin-pro route files
├── storage/             # Runtime storage, logs, generated files, and local keys
├── tests/               # PHPUnit example unit and feature tests
├── composer.json        # PHP dependencies and Composer hooks
├── package.json         # npm scripts and frontend dependencies
├── phpunit.xml          # PHPUnit test configuration
└── vite.config.js       # Vite/Laravel plugin configuration
```

## Main Application Areas

`app/Providers/RouteServiceProvider.php` wires route files into several areas:

- Main client/public web routes: `routes/web.php`
- Admin subdomain routes: `routes/admin.php`
- Staff subdomain routes: `routes/staff.php`
- Pro client subdomain routes: `routes/pro.php`
- Admin Pro subdomain routes: `routes/admin-pro.php`
- Team route files: `routes/team.php` and `routes/team-pro.php`
- API callback routes: `routes/api.php`

For local subdomain testing, set `APP_DOMAIN` to a local domain you control, then configure your hosts/DNS so subdomains such as `admin`, `staff`, `pro`, and `admin-pro` resolve to the Laravel server. Keep `APP_URL` aligned with the URL used to access the app.

## Prerequisites

- PHP compatible with the locked dependencies. `composer.json` allows PHP `^8.0.2`, and the current lock file contains packages capped at PHP 8.2, so use PHP 8.0.2 through PHP 8.2 for the least friction.
- Composer 2.
- Node.js and npm.
- MySQL or a compatible database.
- Optional external binaries/services for features that need them, such as wkhtmltopdf/wkhtmltoimage, mail delivery, SMS gateways, payment gateways, Redis, or M-Pesa callbacks.

## Environment Setup

Copy the example file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use this copy command instead:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Do not commit `.env`. Use `.env.example` as the safe placeholder reference.

Important environment groups in `.env.example`:

- App: `APP_NAME`, `APP_ENV`, `APP_STAGE`, `APP_TYPE`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `APP_DOMAIN`, `TIME_ZONE`.
- Database: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DATABASE_URL`, `MYSQL_ATTR_SSL_CA`.
- Runtime services: `CACHE_DRIVER`, `QUEUE_CONNECTION`, `SESSION_DRIVER`, `FILESYSTEM_DISK`, Redis and Memcached variables.
- Mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`.
- Frontend broadcasting placeholders: `PUSHER_*` and `VITE_PUSHER_*`.
- Storage/cloud placeholders: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_KEY`, `AWS_SECRET`, `AWS_REGION`, `AWS_BUCKET`.
- Payment placeholders: `PAYSTACK_*`, `PAYPAL_*`, `GOPAY_*`, `STRIPE_*`, `MERCHANT_EMAIL`.
- SMS/voice placeholders: `TWILIO_*`, `AMI_*`, `SC_DEVICE`.
- M-Pesa placeholders: `MPESA_*`, `LIPA_NA_MPESA_PASS_KEY`.
- Other integrations: `GOOGLE_RECAPTCHA_SECRET_KEY`, `SUBSCRIPTIONS_EMAIL`, location API tokens, logging tokens, and DataTables settings.

## Installation

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

On Windows PowerShell, use `npm.cmd` if the PowerShell execution policy blocks `npm.ps1`:

```powershell
npm.cmd install
```

## Database Setup

Create a local MySQL database matching `DB_DATABASE`, then run migrations:

```bash
php artisan migrate
```

The current `database/seeders/DatabaseSeeder.php` is empty, so there is no documented seed command required for baseline setup.

Important maintenance note: this legacy app has many models beyond the current migration set. A clean database can run the existing migrations, but some full application areas may require additional sanitized migrations or data imports before they are fully usable. Local SQL dump files in this snapshot are intentionally ignored by `.gitignore` and should not be committed if they contain production or sensitive data.

## Running Locally

Start the Laravel server:

```bash
php artisan serve
```

Start Vite for frontend development:

```bash
npm run dev
```

Build frontend assets:

```bash
npm run build
```

The npm scripts call Vite through `node ./node_modules/vite/bin/vite.js`. This avoids Windows command shim issues when the project path contains special shell characters.

## Local URLs

Default main app URL:

```txt
http://localhost:8000
```

Subdomain route areas are configured from `APP_DOMAIN`. For example, with a local domain such as `localhost.test`, configure these hostnames to resolve locally:

```txt
admin.localhost.test
staff.localhost.test
pro.localhost.test
admin-pro.localhost.test
```

Then set:

```env
APP_URL=http://localhost.test:8000
APP_DOMAIN=localhost.test
```

Use only local/test domains and placeholder credentials in development.

## Available Commands

Composer commands:

```bash
composer install
composer validate --strict
```

npm scripts from `package.json`:

```bash
npm run dev
npm run build
```

Common Laravel commands for this project:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
php artisan test
```

Scheduled application commands are registered in `app/Console/Kernel.php` for SMS scheduling, bulk SMS, recurring SMS, keyword validity, recurring invoices, Pro subscription cleanup, OTP cleanup, and Pro summaries. Use Laravel's scheduler setup for environments where these background jobs are required.

## Testing

PHPUnit is configured by `phpunit.xml`, with test directories:

```txt
tests/Unit
tests/Feature
```

Run tests with:

```bash
php artisan test
```

The current test files are Laravel example tests. Add feature coverage before relying on automated tests for payment, SMS, invoice, route, or permission changes.

## Build Process

Development build:

```bash
npm run dev
```

Production frontend build:

```bash
npm run build
```

Vite outputs built assets to `public/build`, which is ignored because it is generated output.

## Integrations

The codebase contains integration packages, configuration files, controllers, or route callbacks for:

- SMS/communications: Africa's Talking, Twilio, Plivo, Vonage, Telesign, OSMS, custom SMS gateways, SMSGateway.me-style library code, and AMI configuration.
- Payments: PayPal, Paystack, Stripe, GoPay, M-Pesa/Daraja, CoinPayments, Slydepay, OpenPayU, and manual payments.
- Files/PDF/export: DomPDF, Snappy/wkhtmltopdf, Maatwebsite Excel.
- Cloud/storage/mail: AWS configuration, Laravel mail drivers, Mailgun/SES/SparkPost placeholders.
- Security/verification: Google reCAPTCHA and Laravel Sanctum.

Only configure integrations you need for the feature being tested. Never place real credentials in documentation or committed files.

## GitHub Safety Requirements

Before committing or pushing:

- Confirm this project is inside the intended Git repository.
- Do not commit `.env` or any `.env.*` file except `.env.example`.
- Do not commit local SQL dumps, generated logs, generated caches, runtime key files, or dependency folders.
- Keep `.env.example` placeholder-only.
- Rotate any real credentials that were ever present in old local snapshots or shared files.
- Re-run a secret scan before pushing.

Ignored sensitive/generated paths include:

```txt
.env
.env.*
storage/*.key
storage/*.ini
storage/logs/*
storage/framework/*
bootstrap/cache/*
*.sql
node_modules/
vendor/
public/build/
```

## Known Local Constraints

- Composer install requires a PHP version compatible with the lock file. PHP 8.4/8.5 is too new for several locked packages in this snapshot.
- No Docker setup was found.
- No GitHub Actions workflow was found.
- The project has many legacy integration paths; test the specific provider flow you change rather than assuming all providers are configured.
- The migration set does not cover every model present in `app/Models`; extend migrations with sanitized schema changes as needed.

## Documentation Evidence

This README is based on the current project files:

- `composer.json`
- `package.json`
- `.env.example`
- `.gitignore`
- `vite.config.js`
- `phpunit.xml`
- `config/*.php`
- `routes/*.php`
- `app/Http/Controllers/**`
- `app/Models/**`
- `app/Console/Kernel.php`
- `database/migrations/**`
- `database/seeders/DatabaseSeeder.php`
- `resources/views/**`
