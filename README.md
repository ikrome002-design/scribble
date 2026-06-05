# Scribble by Citrus / Citrus SMS Service

## Overview

Scribble by Citrus is a legacy Laravel SMS platform whose application config identifies the app as `Citrus SMS Service`. It is built for administrators and clients who need to manage SMS sending, contacts, SMS gateways, sender IDs, invoices, support tickets, plans, bundles, and payment workflows from a web dashboard.

The codebase is a full Laravel application stored in `application/`, with root-level public entry files (`index.php`, `main.php`) and shared public assets in `assets/`. The root `index.php` performs PHP extension and writable-directory checks before bootstrapping Laravel through `main.php`.

The previous README was only a short GitHub-readiness note. It did not explain the application domain, route surface, database setup, authentication model, integrations, or the legacy runtime constraints, so this README documents those items from the actual files in this repository.

## Key Features

- Admin and client login, registration, verification, password reset, and dashboards.
- Separate admin and client account management using Laravel session guards.
- Client/user management, groups, profile images, bulk import/export, and email/SMS actions.
- Contact lists, blacklist contacts, imported phone numbers, and CSV-based workflows.
- SMS coverage, operators, sender ID management, HTTP/SMPP/custom gateway configuration, quick SMS, bulk SMS, file-based SMS, scheduled SMS, recurring SMS, templates, inbox, history, and delivery/reply callbacks.
- Public SMS API endpoint at `sms/api` plus callback routes for multiple gateways.
- Invoices, recurring invoices, PDF/download/print views, payment status changes, and client invoice views.
- SMS plans, plan features, bundles, unit purchases, and keyword purchases.
- Support tickets, departments, replies, and ticket file downloads.
- Admin roles and permissions.
- System settings for application profile, localization, language, email, payment gateways, background jobs, and gateway/API configuration.
- Database migrations and seeders for app config, clients, admins, groups, contacts, gateways, payment gateways, language data, email templates, and SMS history.

## Project Type

- Full-stack web app
- Laravel app
- Admin dashboard
- Client self-service dashboard
- SMS/SaaS-style billing platform

This is not a static site and not a standalone frontend app. The frontend is server-rendered Blade views with legacy JavaScript/CSS assets and optional Laravel Mix builds.

## Tech Stack

### Backend

- PHP application using Laravel `5.4.*`
- Composer dependencies from `application/composer.json` and `application/composer.lock`
- Laravel session authentication with `admin` and `client` guards
- Laravel API token guard for clients
- Artisan commands in `application/app/Console/Commands`

### Frontend

- Blade templates under `application/resources/views`
- Static assets under root `assets/`
- Laravel Mix `0.6.0`
- Webpack through Laravel Mix
- Vue `^2.0.1`
- jQuery, Bootstrap, Bootstrap Sass, Font Awesome, Moment, DataTables, and other vendored browser libraries

### Database

- MySQL is the configured default database connection.
- PostgreSQL and SQLite configs exist in `application/config/database.php`, but Docker local development is configured for MySQL 5.7.
- The repository includes 43 migration files under `application/database/migrations`.
- Seeders live in `application/database/seeds`.

### Tooling

- Composer for PHP dependencies.
- npm for frontend dependencies.
- Docker Compose for the recommended local runtime.
- PHPUnit `~5.7` is listed in `require-dev`; the repository has basic example tests but no custom `phpunit.xml`.
- No `.github` CI/CD workflow directory is present.

## Project Structure

```txt
project-root/
|-- index.php                    # Public entry with PHP/runtime checks
|-- main.php                     # Boots Laravel and binds the public path to project root
|-- assets/                      # Public CSS, JS, images, browser libraries, and upload-style folders
|-- application/
|   |-- app/                     # Controllers, models, mail classes, jobs, console commands, helpers
|   |-- bootstrap/               # Laravel bootstrap files and cache directory
|   |-- config/                  # Laravel and integration configuration
|   |-- database/                # Migrations, factories, seeders
|   |-- packages/                # Local package code
|   |-- resources/views/         # Blade layouts and admin/client/email/error views
|   |-- routes/                  # Web, API, console, and channel route files
|   |-- storage/                 # Laravel runtime storage; generated contents are ignored
|   |-- tests/                   # Basic Feature and Unit example tests
|   |-- artisan                  # Laravel CLI entry point
|   |-- composer.json            # PHP dependencies and Composer scripts
|   |-- package.json             # npm scripts and frontend dependencies
|   `-- webpack.mix.js           # Laravel Mix build definition
|-- Dockerfile                   # PHP 7.4 Apache compatibility container
|-- docker-compose.yml           # App, MySQL, and Node compatibility services
|-- application/.env.example     # Safe local environment template
`-- README.md
```

## Main Routes And URLs

When running with Docker, the app is served at:

- Client login: `http://localhost:8080/`
- Admin login: `http://localhost:8080/admin`
- Client registration: `http://localhost:8080/signup`
- Public SMS API route: `http://localhost:8080/sms/api`
- Laravel API user route: `http://localhost:8080/api/user` with the `auth:api` middleware

The web route file contains 507 route declarations. Major route groups include:

- `clients/*` for admin-side client management.
- `invoices/*` for admin-side invoice management.
- `administrators/*` for admin users, roles, and permissions.
- `support-tickets/*` for admin ticket workflows.
- `settings/*` for application, language, email, payment, and background-job settings.
- `sms/*` for coverage, sender IDs, gateways, quick/bulk/file/scheduled SMS, templates, API docs, callbacks, inbox, and history.
- `user/*` and `user/sms/*` for client self-service features.

## Authentication

Authentication is configured in `application/config/auth.php`:

- Default guard: `admin`
- `admin` guard: session auth backed by the `Admin` model
- `client` guard: session auth backed by the `Client` model
- `api` guard: token auth backed by the `Client` model

The main auth routes are implemented through `AuthController`.

## Integrations

The codebase includes configuration, dependencies, routes, or controllers for several integrations. Configure real credentials only in `application/.env`; never commit them.

- SMS/telephony: Twilio, Nexmo/Vonage, Plivo, Africa's Talking, Telesign, OVH, SMPP, custom HTTP SMS gateways, and several bundled gateway libraries.
- Payment: Stripe, PayPal, Paystack, M-Pesa, GoPay, PayNow, Slydepay, CinetPay, Moka, WebXPay, and other payment handlers visible in routes/controllers.
- Email/cloud: SMTP, Mailgun, SES, SparkPost, AWS filesystem config.
- Security/verification: Google reCAPTCHA placeholders in `application/.env.example`.
- Asterisk/AMI: `application/config/ami.php` and related Composer dependencies.

## Prerequisites

Recommended local setup:

- Docker Desktop with the Linux engine running
- Docker Compose
- Git

The host machine in this cleanup environment had PHP 8.5, which does not boot this Laravel 5.4 app. Use the Docker PHP 7.4 compatibility container instead of trying to run Artisan with modern host PHP.

## Environment Variables

Use `application/.env.example` as the source of safe placeholder values:

```powershell
Copy-Item application/.env.example application/.env
```

On macOS/Linux:

```bash
cp application/.env.example application/.env
```

Important variable groups in `application/.env.example`:

- App/runtime: `APP_NAME`, `APP_ENV`, `APP_STAGE`, `APP_TYPE`, `APP_KEY`, `APP_DEBUG`, `APP_URL`, `TIME_ZONE`, `TIMEOUT`, `REDIRECT_HTTPS`
- Database/cache/session: `DB_*`, `CACHE_DRIVER`, `SESSION_DRIVER`, `QUEUE_DRIVER`, `REDIS_*`, `MEMCACHED_*`
- Mail: `MAIL_*`, `MAILGUN_*`, `SES_*`, `SPARKPOST_SECRET`
- Cloud/broadcasting: `AWS_*`, `PUSHER_*`
- SMS/telephony: `TWILIO_*`, `SC_DEVICE`, `AMI_*`
- Captcha: `CAPTCHA_SITE_KEY`, `CAPTCHA_SECRET_KEY`
- Payment: `STRIPE_*`, `PAYPAL_*`, `PAYSTACK_*`, `MERCHANT_EMAIL`, `GOPAY_*`
- M-Pesa: `MPESA_*`

Do not place real secrets in `application/.env.example`.

## Local Development With Docker

Build the PHP compatibility image:

```powershell
docker compose build app
```

Install PHP dependencies:

```powershell
docker compose run --rm app composer install
```

Generate a local Laravel app key:

```powershell
docker compose run --rm app php artisan key:generate
```

Start the database and app:

```powershell
docker compose up -d db
docker compose up app
```

Open `http://localhost:8080`.

The Docker Compose file defines:

- `app`: PHP 7.4 Apache container, serving port `8080`
- `db`: MySQL 5.7, exposed on host port `3307`
- `node`: Node 12 service for legacy npm scripts

## Database Setup

After creating `application/.env` and installing Composer dependencies, run migrations:

```powershell
docker compose run --rm app php artisan migrate
```

Optional seed command:

```powershell
docker compose run --rm app php artisan db:seed
```

There is a root SQL dump named `fredcheg_sms (1).sql`, but it is intentionally ignored because it contains credential-like values and likely private data. Prefer migrations and safe seeders for local development.

## Frontend Build

The actual npm scripts in `application/package.json` are:

```powershell
docker compose run --rm node npm run dev
docker compose run --rm node npm run watch
docker compose run --rm node npm run hot
docker compose run --rm node npm run production
```

Install frontend dependencies first:

```powershell
docker compose run --rm node npm install
```

The Laravel Mix file compiles:

- `application/resources/assets/js/app.js` to `application/public/js`
- `application/resources/assets/sass/app.scss` to `application/public/css`

The current checkout primarily contains root-level public assets under `assets/`. If the `resources/assets` inputs are absent in your branch, the Mix build will need those source files restored before it can succeed.

## Tests And Quality Checks

The repository has basic example tests:

- `application/tests/Feature/ExampleTest.php`
- `application/tests/Unit/ExampleTest.php`

PHPUnit is listed in Composer dev dependencies, but there is no custom `phpunit.xml` or `phpunit.xml.dist`. After Composer dependencies are installed, run:

```powershell
docker compose run --rm app vendor/bin/phpunit tests
```

Other useful validation commands:

```powershell
docker compose config
docker compose run --rm app php artisan --version
docker compose run --rm app php artisan route:list
```

In this cleanup environment, `docker compose config` passed, but Docker Desktop's Linux engine was not running, so container build, install, migration, and startup checks could not be completed here.

## GitHub Safety Notes

- Do not commit `application/.env` or any real `.env.*` files.
- Do not commit SQL dumps, runtime logs, Laravel sessions, compiled views, payment transaction files, uploaded media, `application/vendor/`, or `application/node_modules/`.
- `application/config/mpesa.php` now reads M-Pesa values from environment variables instead of hard-coded values.
- Seeded SMTP/API/reCAPTCHA credential-like defaults were removed or replaced with env placeholders.
- Rotate any credentials that were previously present in old dumps, config files, logs, or seeders.
- Review `git status --ignored` before committing to confirm sensitive generated files remain ignored.

## Useful Commands

```powershell
# Git status and ignored-file review
git status --short --ignored

# Validate Docker Compose syntax
docker compose config

# Validate Composer metadata
docker compose run --rm app composer validate --no-check-publish

# Start the app
docker compose up app

# Run migrations
docker compose run --rm app php artisan migrate

# Run basic tests
docker compose run --rm app vendor/bin/phpunit tests
```

## Maintenance Notes

- This is a legacy Laravel 5.4 codebase. Avoid broad framework or dependency upgrades unless you first prove the exact failure being fixed.
- Keep `application/composer.lock`; it is the lockfile for the Laravel app.
- No root `package.json`, Python `requirements.txt`, Python `pyproject.toml`, `package-lock.json`, `yarn.lock`, or `pnpm-lock.yaml` was found for the main app.
- No CI/CD workflows were found in `.github`.
- The route file is large and controller-heavy; make small, targeted changes and validate the affected route/controller/model path.
