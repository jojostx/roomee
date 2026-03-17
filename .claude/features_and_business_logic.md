# Roomee: Features and Business Logic

This document reflects the current codebase after a repo review on 2026-03-17. It records implemented behavior first, then the smaller backlog that still remains.

## Implemented Stack

| Layer | Technology | Status |
|---|---|---|
| Backend framework | Laravel 12 | Implemented |
| Admin panel | Filament 4 | Implemented |
| Reactive UI | Livewire 3 | Implemented |
| Realtime transport | Laravel Reverb | Implemented |
| Browser event client | Laravel Echo + pusher-js | Implemented |
| Frontend build | Vite + Tailwind CSS + Alpine.js | Implemented |

## Implemented Product Behavior

### Core Entities and User Management

- User profiles store name, email, avatar, bio, gender, school, course, course level, preferred room count, hobbies, dislikes, preferred towns, and budget range.
- Roles are `Admin`, `Staff`, and `User`. Admin and staff users can access the Filament panel.
- Onboarding is sequential and profile completion is gated.
- Identity verification supports `unverified`, `pending`, `approved`, and `rejected`.
- Users can be suspended with suspension metadata.
- Premium users can keep multiple published listings. Non-premium users are limited to one active published listing.
- Users can configure multiple contact channels, and each channel has its own enabled and verified state.

### Matching and Discovery

- User discovery is restricted to users in the same school.
- Similarity scoring is weighted across budget overlap, dislikes, preferred towns, preferred room count, hobbies, and course level.
- Budget overlap is also applied as a hard database prefilter before in-PHP similarity scoring runs.
- Gender visibility is bidirectional:
  - if the viewer enables strict gender filtering, they only see users of the same gender
  - a user with a gender set is only visible to same-gender viewers unless that user opted out of strict gender filtering
- Blocking is mutual at the discovery level: blocked users and users who blocked you are both excluded.
- Favorites are implemented and can also act as a filtered discovery source.

### Listings

- Listings store title, description, address, city, rent amount, rent period, move-in date, amenities, house rules, images, and published state.
- Listing management is gated by `canManageListings()`: regular user, verified email, completed profile, approved identity verification, and not suspended.
- Listing discovery applies:
  - budget filtering, with listing preferences falling back to profile budgets
  - move-in-date filtering, including exclusion of already-expired listings
  - dealbreaker filtering against listing house rules
  - amenity similarity scoring based on the seeker's stored preferences

### Requests, Chat, and Contact Sharing

- Roommate requests support `pending`, `accepted`, `denied`, and deletion flows.
- Accepting a roommate request creates or reuses a `chat_rooms` record for the pair.
- Chat data is stored in `chat_rooms` and `chat_messages`.
- `ChatRoom` tracks `contact_shared_by_a` and `contact_shared_by_b`, and exposes `hasBothSharedContacts()`.
- Contact details remain hidden until both matched users share contacts inside chat.
- The active chat experience is the global `ChatDrawer` overlay:
  - it lists rooms with latest message previews and unread counts
  - it supports live message sending and read tracking
  - it shows consent controls for sharing or withdrawing contact sharing
  - it unlocks the contact modal only after mutual consent
- `/chat/{room}` routes currently exist as wrappers that auto-open the global drawer with the selected room.

### Settings, Notifications, and Admin

- Account settings include profile data, password changes, and matching preferences.
- Contact-channel settings support per-channel setup and verification.
- Notification settings page exists.
- Realtime events are implemented for roommate requests, chat messages, and blocking updates.
- Filament resources and pages exist for users, listings, roommate requests, favorites, blocklists, contact channels, verification requests, verification settings, and admin broadcasts.

## Remaining Work

- Harmonize CTA labels and modal copy so every surface matches the mutual-consent chat flow.
- Decide whether chat should remain drawer-first or whether `ChatIndexPage` and `ChatRoomPage` should become full standalone page UIs.
- If desired, wire dashboard and roommate-request actions to open and select the drawer inline instead of redirecting to `/chat/{room}`.
- Clean up stale Filament v3 CSS classes and related legacy styling backlog still present in several Blade views.
