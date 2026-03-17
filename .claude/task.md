# Current Task Tracker

Reviewed against the live codebase on 2026-03-17.

## Done

- [x] Chat rooms and chat messages tables and models exist.
- [x] Accepting a roommate request creates or reuses a chat room.
- [x] Mutual contact sharing is required before contact channels unlock.
- [x] The global chat drawer supports room lists, unread counts, realtime messages, and read tracking.
- [x] Listing discovery rules, premium listing limits, and admin moderation resources are in place.
- [x] Automated tests cover chat room creation, chat drawer behavior, listings, matching, middleware, and Filament smoke rules.

## Remaining

- [ ] Harmonize chat and contact labels plus modal copy across dashboard, favorites, profile views, and roommate-request pages.
- [ ] Decide whether to keep drawer-first chat with placeholder `/chat` pages or build full standalone chat page content.
- [ ] If desired, wire dashboard and roommate-request actions to open and select the chat drawer inline instead of redirecting.
- [ ] If desired, add an unmatch action and page-level tests for dashboard/request chat actions.
- [ ] Clean up stale Filament v3 CSS classes and any related non-panel styling backlog still present in Blade views.
