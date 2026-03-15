# Implementation Plan: In-App Chat & Contact Sharing

This document outlines the plan to build an in-app chat system and modify the existing contact sharing business logic so that contacts are only revealed after mutual consent within the chat.

## Current Flow vs. New Flow
**Current Flow**: User A sends Roommate Request -> User B accepts -> Both can view each other's contact channels immediately via a modal.
**New Flow**: User A sends Roommate Request -> User B accepts -> A new `ChatRoom` is created between A and B. Contact channels are **hidden**. -> They chat. -> If both A and B click "Share Contacts" in the chat, the contact modal becomes accessible to both.

## Proposed Changes

### 1. Database Migrations & Models
We need tables to support real-time chat and tracking contact-sharing consent.

#### [NEW] `create_chat_rooms_table` Migration
- `id` (UUID), `user_a_id`, `user_b_id`, `contact_shared_by_a` (boolean, default false), `contact_shared_by_b` (boolean, default false), timestamps.
- Add unique constraint on [(user_a_id, user_b_id)](file:///c:/Users/USER/Desktop/code/roomee/app/Models/User.php#38-550).

#### [NEW] `create_chat_messages_table` Migration
- `id` (UUID), `chat_room_id`, `sender_id`, `message` (text), `read_at` (timestamp), timestamps.

#### [NEW] `app/Models/ChatRoom.php`
- Relationships: `messages()`, `participants()` (or `userA()` and `userB()`).
- Helper methods: `hasBothSharedContacts()` (returns true if `contact_shared_by_a` and `contact_shared_by_b` are true).

#### [NEW] `app/Models/ChatMessage.php`
- Relationships: `room()`, [sender()](file:///c:/Users/USER/Desktop/code/roomee/app/Models/RoommateRequest.php#55-62).

### 2. Business Logic & Controllers

#### [MODIFY] [app/Models/RoommateRequest.php](file:///c:/Users/USER/Desktop/code/roomee/app/Models/RoommateRequest.php) or `AcceptRoommateRequest` Action
- When a roommate request transitions to **Accepted**, dispatch an event or directly create a `ChatRoom` record for the sender and recipient.

#### [MODIFY] [app/Models/User.php](file:///c:/Users/USER/Desktop/code/roomee/app/Models/User.php)
- Add a relationship `chatRooms()`.
- Modify the logic that dictates when a user can view another user's contacts. Currently, it likely checks if [RoommateRequest](file:///c:/Users/USER/Desktop/code/roomee/app/Models/RoommateRequest.php#16-120) is accepted. It must now check: [RoommateRequest](file:///c:/Users/USER/Desktop/code/roomee/app/Models/RoommateRequest.php#16-120) is accepted AND `ChatRoom::hasBothSharedContacts()` is true.

### 3. Livewire & Frontend

#### [NEW] `app/Livewire/Pages/Chat/ChatIndexPage.php` & `ChatRoomPage.php`
- `ChatIndexPage`: A sidebar listing all active chat rooms (accepted roommate requests). Displays latest message and unread count.
- `ChatRoomPage`: The actual conversational interface displaying `ChatMessage`s. Uses Laravel Echo/Pusher (already in [package.json](file:///c:/Users/USER/Desktop/code/roomee/package.json)) to listen for new messages instantly.

#### [MODIFY] [app/Livewire/Pages/DashboardPage.php](file:///c:/Users/USER/Desktop/code/roomee/app/Livewire/Pages/DashboardPage.php) & Other User Card Views
- Modify the "Contact User" action button.
- Instead of opening the `contact-user-modal`, it should now say "Message" and redirect to the `ChatRoomPage` for that user.
- The `contact-user-modal` logic should be restricted so it can only be triggered from inside the `ChatRoomPage` *after* the mutual consent boolean is true.

#### [MODIFY] `ChatRoomPage` (Contact Sharing Flow)
- Add a UI banner/button at the top of the chat: "Would you like to share contact info?".
- When a user clicks it, it sets their respective `contact_shared_by_x` column to `true`.
- Emit a real-time event so the other user sees "User X wants to share contacts. [Accept]".
- Once both are true, the chat header updates to include a "Show Contact Channels" button, which opens the existing `contact-user-modal`.

## Verification Plan
### Automated Tests
- Create tests ensuring `ChatRoom`s are predictably created on Roommate Request acceptance.
- Create tests verifying that contact models/channels are hidden if `hasBothSharedContacts` is false.

### Manual Verification
1. Log in as User A, send request to User B.
2. Log in as User B, accept request. Verify redirection/access to Chat Room.
3. Test Real-time messaging using Laravel Echo/Soketi.
4. Verify the contact modal is strictly inaccessible.
5. User A clicks "Share contact", verify it updates state. User B clicks "Share contact", verify the modal finally unlocks for both.
