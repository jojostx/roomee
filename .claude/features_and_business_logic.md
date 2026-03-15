# Roomee: Features and Business Logic Documentation

This document outlines features and core business logic in the Roomee application, marked by implementation status.

**Legend**: ✅ Implemented · 🚧 Planned (not yet built)

---

## Infrastructure & Tech Stack

| Layer | Technology | Status |
|---|---|---|
| Backend framework | Laravel 12 | ✅ |
| Admin panel | Filament v4 | ✅ |
| Reactive UI | Livewire v3 | ✅ |
| Real-time WebSockets | Laravel Reverb | ✅ |
| Frontend event bus | Laravel Echo + pusher-js | ✅ |

---

## Core Entities & User Management

### ✅ User Profiles
Users create profiles storing: name, email, avatar, bio, gender, school, course of study, course level, preferred number of rooms, hobbies, dislikes, preferred towns, and budget range (`min_budget`, `max_budget`).

### ✅ Roles & Permissions
Three roles: `Admin`, `Staff`, and `User` (Regular). Admins and Staff access the Filament backend panel. Regular users interact with the core social features.

### ✅ Onboarding & Verification
- Users go through a sequential onboarding flow gating profile sections (General → Personal → Educational → Apartment).
- Identity verification has four statuses: `unverified`, `pending`, `approved`, `rejected`.
- Users can be suspended (`is_suspended`).

### ✅ Premium Users
Users flagged as premium (`is_premium`) can publish **multiple listings simultaneously**. Non-premium users are limited to one active listing at a time.

### ✅ Contact Channels
Users configure multiple contact methods (Email, WhatsApp, Facebook, Instagram, Twitter, etc.) and have each individually verified. When and how channels are shared with a matched user is governed by the contact-sharing flow (see **User Interactions** below).

---

## Matching & Discovery

### ✅ School-Scoped Discovery
All user discovery is strictly scoped to users attending the **same school**. Users from different schools are never surfaced to one another in any discovery context.

### ✅ Similarity Scoring
A weighted similarity algorithm ranks potential roommates with a normalised score (0–100) across six factors:

| Factor | Weight |
|---|---|
| Budget range overlap | 1.4 (highest) |
| Dislikes overlap | 1.0 |
| Preferred towns overlap | 1.0 |
| Preferred number of rooms | 1.0 |
| Course level | 0.8 |
| Hobbies overlap | 0.8 |

Budget overlap is a pre-filter (hard filter) applied at the database query level before in-PHP scoring; only users whose budget ranges overlap are considered as similarity candidates.

### ✅ Gender Filtering (Bidirectional Visibility)
Gender filtering affects both who a user sees **and** who can see them:
- If a user has **strict gender filtering enabled**, they only see users of the same gender.
- A user with a gender set will only **appear** in others' discovery results if those others share the same gender — unless the discovering user has set `strict_gender_filter: false`, in which case they are visible across genders.

### ✅ Property Listings
Users can post roommate/property listings. Listings include rent amount, rent period, amenities, address, move-in date, and specific house rules (e.g., no smoking, no pets).

**Prerequisites** (`canManageListings`): User must be a regular (non-admin/staff) user, have a verified email, completed profile, **approved** identity verification status, and must not be suspended.

### ✅ Listing Discovery
Users configure listing preferences (stored in `settings->listing_preferences`):
- **Budget filtering**: Listings filtered to those within the user's preferred budget range (falls back to profile budget range if no listing-specific range is set).
- **Move-in date filtering**: Listings with a passed move-in date are always excluded. If the user has a preferred move-in date set, listings with a later date are also excluded.
- **Dealbreakers**: Listings with conflicting house rules (e.g., user wants pets; listing says no pets) are hard-filtered out.
- **Amenity similarity scoring**: Listings scored against preferred amenities using Jaccard similarity (0–100). If no amenity preference is set, listings receive a full 100 score on this axis. This score is computed separately from user-to-user similarity.

---

## User Interactions

### ✅ Roommate Requests
The core interaction loop. Users send roommate requests. A request can be: `pending`, `accepted`, `denied`, or `deleted`.

### ✅ Favoriting
Users can bookmark ("favorite") other users. Favorites are also used as a filtering signal in discovery pages.

### ✅ Blocking
Users can block others. Blocking enforces **mutual exclusion** in all discovery scopes:
- A user will not see anyone they have blocked.
- A user will not see anyone who has blocked them.
- A block by either party removes both users from each other's discovery results simultaneously.

### ✅ Reporting
Users can report others for violating platform guidelines. Reports are selected from a predefined list and reviewed by admins.

---

## Contact Sharing

### ~~Legacy Flow~~ (Replaced)
~~When a roommate request is accepted, both users can immediately view each other's contact channels via the `contact-user-modal`.~~

### ✅ New Flow (Implemented)
The immediate-reveal behaviour above will be replaced by a consent-gated chat flow:

1. User A sends a roommate request to User B.
2. User B accepts → a **ChatRoom** is created for A and B; contact channels remain hidden.
3. A and B communicate via in-app chat (real-time via Laravel Reverb).
4. If **both** A and B click "Share Contacts" within the chat, their respective consent flags (`contact_shared_by_a`, `contact_shared_by_b`) are set to `true`.
5. Only once **both** flags are `true` does the contact modal unlock, showing each user the other's verified channels.

**New entities required**:
- `chat_rooms` table — `id` (UUID), `user_a_id`, `user_b_id`, `contact_shared_by_a` (bool), `contact_shared_by_b` (bool), unique constraint on `(user_a_id, user_b_id)`.
- `chat_messages` table — `id` (UUID), `chat_room_id`, `sender_id`, `message` (text), `read_at` (nullable timestamp).
- `ChatRoom` model with `hasBothSharedContacts()` helper.
- `ChatMessage` model.

**New UI required**:
- `ChatIndexPage` — sidebar listing all active chat rooms with latest message and unread count.
- `ChatRoomPage` — real-time conversational interface. Includes a "Share Contacts" consent banner/button. Once mutual consent is given, a "Show Contact Channels" button appears.

**Modifications required**:
- `RoommateRequest` acceptance logic — create a `ChatRoom` when a request transitions to `accepted`.
- `User` model — add `chatRooms()` relationship; update contact-viewing gate to check `hasBothSharedContacts()` in addition to accepted request status.
- `DashboardPage` / `RoommateRequestsPage` — change "Contact User" action to "Message" (redirects to `ChatRoomPage`); disable direct contact modal access outside the chat context.

---

## Settings & Preferences

### ✅ Account Settings
Users can update their first/last name, email address (requires re-verification of new address), and password.

### ✅ Matching Preferences
Users can enable/disable strict gender filtering, set listing budget range, preferred move-in date, and dealbreakers — all of which influence what listings and users they see in discovery.

### ✅ Contact Channel Settings
Users can configure and individually verify each of their contact channels (Email, WhatsApp, Facebook, Instagram, Twitter).

### ✅ Notification Settings
Basic notification preference management.
