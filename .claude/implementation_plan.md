# Chat and Contact Sharing Status

This file replaces the earlier future-state plan. After reviewing the repo on 2026-03-17, most of the original chat and consent-based contact-sharing plan is already implemented.

## Implemented

### 1. Data Model

- `create_chat_rooms_table` migration exists.
- `create_chat_messages_table` migration exists.
- `app/Models/ChatRoom.php` exists with:
  - `userA()` and `userB()` relationships
  - `messages()` relationship
  - `otherParticipant()`
  - `hasBothSharedContacts()`
  - `markContactSharedBy()`
  - `markContactUnsharedBy()`
  - `hasUserSharedContacts()`
  - `findBetween()` and `firstOrCreateBetween()`
- `app/Models/ChatMessage.php` exists.

### 2. Business Flow

- Accepting a roommate request creates or reuses a chat room via `ChatRoom::firstOrCreateBetween()`.
- Contact details are gated behind both:
  - an accepted roommate relationship
  - mutual contact sharing inside the chat room
- Contact modal access in `WithUserActionModals` already enforces that gate.

### 3. Realtime and UI

- Chat messaging is implemented through the global `ChatDrawer` Livewire component.
- The drawer supports:
  - room selection
  - unread counts
  - read tracking
  - live message sending
  - realtime updates through `MessageSent`
  - share and unshare contact actions
  - contact modal unlock after mutual consent
- `resources/views/layouts/guest.blade.php` auto-opens the drawer on `/chat/*` routes and passes the initial room ID.
- Broadcast channel authorization exists for `chat-room.{chatRoomId}`.

### 4. Test Coverage

- `tests/Feature/Chat/ChatRoomCreationTest.php` covers room creation, contact-sharing rules, chat-room authorization, and basic message sending.
- `tests/Feature/ChatDrawerTest.php` covers drawer state, room selection, message sending, read tracking, contact-channel gating, sharing and unsharing contacts, and chat route authorization.

## Remaining Work

- Dashboard and roommate-request actions still redirect to `/chat/{room}` rather than opening and selecting the drawer inline.
- `ChatIndexPage` and `ChatRoomPage` are currently wrapper routes around the drawer, not full standalone page UIs.
- Chat and contact CTA wording is still inconsistent across dashboard, favorites, request pages, and shared modal actions.
- An explicit unmatch action is not present.
- Older view and CSS cleanup tasks that are unrelated to the core chat flow remain pending.
