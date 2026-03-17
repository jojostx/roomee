# Roomee

Roomee is a student roommate-matching platform built on Laravel 12, Livewire 3, Filament 4, and Laravel Reverb. The current codebase supports school-scoped discovery, roommate requests, verified contact channels, listing discovery, and real-time chat with mutual contact-sharing consent.

## Current Status

Implemented
- Authentication, email verification, onboarding, and identity-verification gating
- School-scoped roommate discovery with weighted similarity scoring and budget prefiltering
- Favorites, blocking, reporting, and real-time roommate-request updates
- Listing creation and discovery with budget, move-in, amenity, and dealbreaker filtering
- Premium listing limits, suspension controls, and Filament admin moderation pages
- Chat rooms created on roommate-request acceptance
- Drawer-based real-time chat with unread counts, read tracking, and mutual contact sharing before contact details unlock

Still open
- Some CTAs and modal copy still use legacy "Contact User" or "Message" wording
- Dashboard and roommate-request actions still redirect to `/chat/{room}` instead of selecting the drawer inline
- `ChatIndexPage` and `ChatRoomPage` are route wrappers around the global drawer, not standalone chat pages
- Old Filament v3 CSS cleanup tasks are still pending in several Blade views

## Core Features

### User and Matching

- Sequential onboarding gates profile completion across general, personal, educational, and apartment sections.
- Users are filtered to the same school before discovery results are shown.
- Similarity scoring uses budget overlap, dislikes, preferred towns, preferred room count, hobbies, and course level.
- Gender visibility is bidirectional: a user's strict gender setting affects both who they see and who can see them.

### Requests, Chat, and Contact Sharing

- Users can send, accept, deny, and delete roommate requests.
- Accepting a request creates a `chat_rooms` record for the pair.
- Contacts stay hidden until both matched users share contacts inside chat.
- The active chat UI is the global `ChatDrawer` overlay, which can also be auto-opened from `/chat/*` routes.
- Verified contact channels include email plus enabled and verified social channels.

### Listings and Admin

- Listings support rent amount, rent period, move-in date, amenities, house rules, and publication state.
- Non-premium users can only keep one published listing; premium users can keep multiple.
- Listing discovery applies budget, move-in-date, dealbreaker, and amenity-similarity logic.
- Filament resources cover users, listings, roommate requests, favorites, blocklists, contact channels, and verification requests.
- Admin and staff users can access the admin panel and broadcast messages to users.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| UI | Livewire 3, Filament 4 |
| Frontend | Vite, Alpine.js, Tailwind CSS |
| Realtime | Laravel Reverb, Laravel Echo, pusher-js |
| Auth | Sanctum, email verification, identity verification |
| Data | MySQL, Redis |
| Testing | PHPUnit 11 |

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
```

For day-to-day development, run:

```bash
php artisan serve
npm run dev
php artisan queue:work
php artisan reverb:start
```

Key realtime environment values come from `.env.example`:

```ini
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## Project Structure

```text
app/
|- Filament/           # admin pages, resources, widgets
|- Livewire/           # user-facing pages, cards, chat drawer, traits
|- Models/             # users, listings, requests, chat rooms/messages, etc.
|- Events/Listeners/   # realtime events and queued listeners
|- Http/Middleware/    # profile, suspension, identity, listing access guards
|- Services/           # similarity helpers
resources/
|- views/              # Blade views, chat drawer, page layouts
|- js/                 # Echo/Reverb bootstrap and Alpine stores
|- css/                # application styles
routes/
|- web.php             # public, authenticated, listings, chat, settings routes
|- channels.php        # private broadcast channel authorization
tests/
|- Feature/            # auth, matching, listings, chat, middleware, Filament
|- Unit/
```

## Testing

Run the full suite with `php artisan test --compact`.

Targeted suites already cover:
- chat room creation and mutual contact sharing
- chat drawer behavior
- listing access and advanced filtering
- matching rules, including budget prefiltering and gender visibility
- onboarding and profile gating
- Filament resource rules and smoke routes
