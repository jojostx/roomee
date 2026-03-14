# Roomee 🏠

A **student roommate-matching platform** built with Laravel 11, Livewire 3, and Filament 3. Roomee helps students find compatible roommates using a weighted similarity algorithm, verify their identity, discover rental listings, and connect with potential roommates in real time.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Running the App](#running-the-app)
- [Real-Time (WebSockets)](#real-time-websockets)
- [Admin Panel](#admin-panel)
- [Project Structure](#project-structure)
- [Key Concepts](#key-concepts)

---

## Features

- **Roommate Matching** — Weighted similarity scoring (hobbies, dislikes, budget, location, course level, room count) shown as a 0–100% match score.
- **Identity Verification (KYC)** — Users upload a government ID and selfie; admins review before granting full access.
- **Roommate Requests** — Send, accept, deny, or delete requests between users.
- **Contact Channels** — WhatsApp, Facebook, Instagram, Twitter, and Email contact details revealed only after a request is accepted.
- **Property Listings** — Post and discover rental listings filtered by budget, location, move-in date, amenities, and house rules.
- **Real-Time Notifications** — WebSocket-powered live notifications for request updates and admin broadcasts.
- **Favorites & Blocklist** — Save interesting profiles or block unwanted users.
- **Reporting System** — Report users directly from the dashboard.
- **Onboarding Flow** — Step-by-step guide using `spatie/laravel-onboard`.
- **Filament Admin Panel** — Manage users, verify identities, moderate content, and broadcast messages to all users.

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.2+, Laravel 11 |
| **UI Components** | Livewire 3, Filament 3 |
| **Frontend** | Vite, Tailwind CSS v3, Alpine.js v3 |
| **Auth** | Laravel Sanctum + Email Verification + KYC |
| **Database** | MySQL 8 |
| **Cache / Queue** | Redis |
| **Real-Time** | Pusher protocol (Soketi / self-hosted WebSockets) + Laravel Echo |
| **File Storage** | Local public disk (avatars) + private disk (KYC docs) |
| **Dev Environment** | Laravel Sail (Docker), MailHog |

---

## Requirements

- PHP **8.2+** with extensions: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`
- **Composer**
- **Node.js** (LTS) + **npm**
- **MySQL 8** (or use Docker / Laravel Sail)
- **Redis**
- **Soketi** (for real-time features) — see [Real-Time](#real-time-websockets)

---

## Installation

```bash
# 1. Clone the repository
git clone <repo-url> roomee
cd roomee

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy the environment file and generate the app key
cp .env.example .env
php artisan key:generate

# 5. Configure your database and other settings in .env (see below)

# 6. Run migrations and seed the database
php artisan migrate --seed

# 7. Create the storage symlink
php artisan storage:link

# 8. Build frontend assets
npm run build
```

### Using Laravel Sail (Docker)

```bash
# Start all services (MySQL, Redis, MailHog)
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate --seed
```

---

## Environment Configuration

Copy `.env.example` to `.env` and fill in the key values:

```ini
APP_NAME=Roomee
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_DATABASE=roomee
DB_USERNAME=root
DB_PASSWORD=

# Queue (use "database" or "redis" for production)
QUEUE_CONNECTION=sync

# Mail (MailHog is preconfigured for local dev)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# WebSockets — Pusher-compatible (see Real-Time section)
PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"

# Enable broadcasting
BROADCAST_DRIVER=pusher
```

---

## Running the App

```bash
# Development server
php artisan serve

# Watch & rebuild frontend assets
npm run dev

# Process queued jobs (notifications, emails)
php artisan queue:work
```

---

## Real-Time (WebSockets)

Roomee uses a Pusher-compatible WebSocket server for real-time roommate request notifications and admin broadcasts. The recommended local setup is **Soketi**.

**Install Soketi:**

```bash
npm install -g @soketi/soketi@latest
```

**Start the WebSocket server** (in a separate terminal):

```bash
soketi start
```

Ensure `BROADCAST_DRIVER=pusher` and the `PUSHER_*` variables in `.env` point to `127.0.0.1:6001`.

> Other Pusher-compatible options (e.g., [Laravel WebSockets](https://beyondco.de/docs/laravel-websockets)) also work — just update the `PUSHER_HOST` and `PUSHER_PORT` accordingly.

---

## Admin Panel

The Filament-powered admin panel is available at `/admin`.

Access requires the `admin` or `staff` user role. From the admin panel you can:

- **Manage Users** — view, edit, suspend, or unsuspend accounts.
- **Review Identity Verifications** — approve or reject KYC submissions (ID + selfie), with secure temporary file URLs.
- **Moderate Content** — manage roommate requests, listings, favorites, and blocklists.
- **Broadcast Messages** — send a notification to all users at once via the "Message Users" page.
- **View Stats** — system overview widget on the dashboard.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/        # Standard + Auth controllers
│   ├── Livewire/Pages/     # Full-page Livewire components
│   └── Middleware/         # Auth, KYC, suspension guards
├── Models/                 # Eloquent models (User, Listing, RoommateRequest, …)
├── Filament/               # Admin panel resources & pages
├── Notifications/          # Queued DB notifications
├── Events/ & Listeners/    # WebSocket broadcast events
└── Services/               # Similarity scoring logic
database/
├── migrations/             # 50+ incremental migrations
└── seeders/
resources/
├── views/
│   ├── livewire/pages/     # Livewire component templates
│   ├── pages/              # Public marketing pages
│   └── auth/               # Auth flow views
└── js/ & css/              # Alpine.js, Laravel Echo, Tailwind
routes/
├── web.php                 # Public + authenticated web routes
└── auth.php                # Auth routes
```

---

## Key Concepts

### Similarity Algorithm

The matching score between two users is calculated in the `canCalculateUserSimilarity` trait using OVRS (Overlap Range Similarity) and Jaccard similarity across weighted dimensions:

| Dimension | Weight |
|---|---|
| Budget overlap | 1.4 |
| Dislikes overlap | 1.0 |
| Preferred locations | 1.0 |
| Room count preference | 1.0 |
| Hobbies overlap | 0.8 |
| Course level | 0.8 |

### Identity Verification Flow

1. User uploads **government ID** + **selfie** (webcam or file) on the profile page.
2. Status changes to `pending`; the user is redirected to a waiting page.
3. Admin reviews the files via signed URLs (30-minute expiry) in `/admin`.
4. On **approval** the user gains full access; on **rejection** the files are deleted and a reason is stored.

### Contact Channel Reveal

A user's social/contact links (WhatsApp, Facebook, Instagram, Twitter, Email) are only visible to another user after a roommate request between them has been **accepted**.