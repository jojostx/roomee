# Roomee: Features and Business Logic Documentation

This document outlines the current features and core business logic already implemented in the Roomee application.

## Core Entities & User Management
* **User Profiles**: Users can create robust profiles storing their name, email, avatar, bio, gender, school, course of study, course level, preferred number of rooms, hobbies, dislikes, preferred towns, and budget range (`min_budget`, `max_budget`).
* **Roles & Permissions**: The application supports three user roles: `Admin`, `Staff`, and `User` (Regular). Admins and Staff can access the Filament backend panel. Regular users interact with the platform's core social features.
* **Onboarding & Verification**:
  * Users go through an onboarding flow.
  * Identity verification is implemented with four distinct statuses: `unverified`, `pending`, `approved`, and `rejected`.
  * Users can also be suspended (`is_suspended`).
* **Premium Users**: Users can be flagged as premium (`is_premium`). The practical effect is that **premium users can publish multiple listings simultaneously**, while non-premium users are restricted to one active listing at a time.
* **Contact Channels**: Users can configure multiple contact methods (Email, WhatsApp, Facebook, Instagram, Twitter, etc.) and have each individually verified. How and when these channels are shared with a matched user is governed by the contact-sharing flow described under **User Interactions** below.

## Matching & Discovery

### School-Scoped Discovery
All user discovery is strictly scoped to users who attend the **same school** as the current user. Users from different schools are never surfaced to one another in any discovery context.

### Similarity Scoring
The application features a weighted similarity algorithm to rank potential roommates. It computes a normalised score (0–100) by comparing six properties:

| Factor | Weight |
|---|---|
| Budget range overlap | 1.4 (highest) |
| Dislikes overlap | 1.0 |
| Preferred towns overlap | 1.0 |
| Preferred number of rooms | 1.0 |
| Course level | 0.8 |
| Hobbies overlap | 0.8 |

Budget overlap is a pre-filter (hard filter) applied at the database query level before expensive in-PHP scoring; only users whose budget ranges overlap are considered as similarity candidates.

### Gender Filtering (Bidirectional Visibility)
Gender filtering affects both who a user sees **and** who can see them:
* If a user has **strict gender filtering enabled**, they only see users of the same gender.
* Independently, a user with a gender set will only **appear** in other users' discovery results if those other users share the same gender — unless the user has explicitly set `strict_gender_filter: false` in their settings, in which case they are visible across genders.

### Property Listings
Users can post roommate/property listings. Listings include rent amount, rent period, amenities, address, move-in date, and specific house rules (e.g., no smoking, no pets).

**Prerequisites for creating or publishing a listing** (`canManageListings`): A user must be a regular (non-admin/staff) user, have a verified email address, have completed their profile update, have an **approved** identity verification status, and must not be currently suspended.

### Listing Discovery
Users can configure listing preferences (stored in `settings->listing_preferences`) that filter and rank the listings they see:
* **Budget filtering**: Listings are filtered to those whose rent falls within the user's preferred budget range. If no explicit listing budget is set, the user's profile budget range is used as the fallback.
* **Move-in date filtering**: Listings whose move-in date has already passed are always excluded. If the user has a preferred move-in date set, listings with a later move-in date are also excluded.
* **Dealbreakers**: Users can set dealbreakers (e.g., "Must allow pets"). Listings with conflicting house rules (e.g., "No Pets") are hard-filtered out.
* **Amenity similarity scoring**: Users can specify preferred amenities. Listings are scored against these preferences using Jaccard similarity (0–100). If a user has no amenity preference set, listings receive a full 100 score on this axis. This score is computed separately from user-to-user similarity.

## User Interactions

### Roommate Requests
The core interaction loop. Users can send roommate requests to one another. A request can have one of four statuses: `pending`, `accepted`, `denied`, or `deleted`.

### Contact Sharing (Planned Flow)
Accepting a roommate request creates a **ChatRoom** between the two users. Contact channels are **not** revealed immediately. The full contact-sharing flow is:
1. User A sends a roommate request to User B.
2. User B accepts — a `ChatRoom` is created for A and B; contact channels remain hidden.
3. A and B communicate in the chat room.
4. If **both** A and B click "Share Contacts" within the chat, their respective consent flags (`contact_shared_by_a`, `contact_shared_by_b`) are set to true.
5. Only once both flags are true does the contact modal unlock, showing each user the other's **verified** contact channels.

Only channels that have been individually verified are ever revealed — configured-but-unverified channels are never exposed.

### Favoriting
Users can bookmark ("favorite") other users. The favorites list is also used within discovery pages as a filtering signal (e.g., to de-emphasise or mark already-favourited users in the feed).

### Blocking
Users can block others. Blocking is enforced as a **mutual exclusion** in all discovery scopes:
* A user will not see anyone they have blocked.
* A user will not see anyone who has blocked them.
* Both directions are excluded simultaneously; a block by either party removes both users from each other's discovery results.

### Reporting
Users can report others for violating platform guidelines.

## Settings & Preferences
* **Matching preferences**: Users can enable strict gender filtering (described under Matching & Discovery above).
* **Listing preferences**: Users can configure their listing discovery filters: preferred budget range, preferred move-in date, dealbreakers, and preferred amenities (all described under Listing Discovery above).
