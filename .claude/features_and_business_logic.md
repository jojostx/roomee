# Roomee: Features and Business Logic Documentation

This document outlines the current features and core business logic already implemented in the Roomee application.

## Core Entities & User Management
* **User Profiles**: Users can create robust profiles storing their name, email, avatar, bio, gender, school, course of study, hobbies, dislikes, preferred towns, and budget range (`min_budget`, `max_budget`).
* **Roles & Permissions**: The application supports different user roles: `Admin`, `Staff`, and [User](file:///c:/Users/USER/Desktop/code/roomee/app/Models/User.php#38-550) (Regular). Admins and Staff can access the Filament backend panels.
* **Onboarding & Verification**: 
  * Users go through an onboarding flow.
  * Identity verification is implemented with distinct statuses: `unverified`, `pending`, `approved`, and `rejected`.
  * The application supports suspending users (`is_suspended`) and flag them as premium (`is_premium`).
* **Contact Channels**: Users can configure multiple contact methods (Email, WhatsApp, etc.). Currently, accepting a roommate request reveals these channels to the matching user.

## Matching & Discovery
* **Similarity Scoring**: The application features a dynamic algorithm to calculate a "Similarity Score" between users. It compares properties such as their courses, preferred towns, hobbies, and dislikes to rank potential roommates.
* **Property Listings**: Users can post roommate/property [Listing](file:///c:/Users/USER/Desktop/code/roomee/app/Models/Listing.php#13-146)s. Listings include rent amounts, periods, amenities, address, move-in dates, and specific house rules (e.g., no smoking, no pets).
* **Listing Discovery**: Users can set up listing preferences in their settings (budget thresholds, dealbreakers) to filter the listings they see on the platform.

## User Interactions
* **Roommate Requests**: The core interaction loop. Users can send roommate requests to one another. Requests can be pending, accepted, or denied. Currently, accepting a request unlocks the ability to view the other person's contact channels.
* **Favoriting**: Users can bookmark or "favorite" other users to view their profiles later.
* **Blocking**: Users can block others, which prevents the blocked user from viewing their profile or sending roommate requests.
* **Reporting**: Users can report others for violating platform guidelines.

## Settings & Preferences
* **Filtering Preferences**: Users can enforce strict gender filtering when viewing potential matches.
* **Listing Preferences**: Users can specify dealbreakers (e.g., "Must allow pets"), which explicitly filter out listings with conflicting house rules (e.g., "No Pets").
