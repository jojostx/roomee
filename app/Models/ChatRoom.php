<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperChatRoom
 */
class ChatRoom extends Model
{
    /** @use HasFactory<\Database\Factories\ChatRoomFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'contact_shared_by_a',
        'contact_shared_by_b',
    ];

    protected $casts = [
        'contact_shared_by_a' => 'boolean',
        'contact_shared_by_b' => 'boolean',
    ];

    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id');
    }

    /**
     * Returns the other participant in the room relative to the given user.
     */
    public function otherParticipant(User $user): ?User
    {
        if ((int) $this->user_a_id === $user->getKey()) {
            return $this->userB;
        }

        return $this->userA;
    }

    public function hasBothSharedContacts(): bool
    {
        return $this->contact_shared_by_a && $this->contact_shared_by_b;
    }

    /**
     * Record that a given user has consented to share their contacts.
     */
    public function markContactSharedBy(User $user): void
    {
        if ((int) $this->user_a_id === $user->getKey()) {
            $this->update(['contact_shared_by_a' => true]);
        } else {
            $this->update(['contact_shared_by_b' => true]);
        }
    }

    /**
     * Revoke a given user's contact-sharing consent.
     */
    public function markContactUnsharedBy(User $user): void
    {
        if ((int) $this->user_a_id === $user->getKey()) {
            $this->update(['contact_shared_by_a' => false]);
        } else {
            $this->update(['contact_shared_by_b' => false]);
        }
    }

    /**
     * Returns whether the given user has already shared their contacts.
     */
    public function hasUserSharedContacts(User $user): bool
    {
        if ((int) $this->user_a_id === $user->getKey()) {
            return $this->contact_shared_by_a;
        }

        return $this->contact_shared_by_b;
    }

    /**
     * Find a chat room between two users regardless of which is user_a / user_b.
     */
    public static function findBetween(User $userA, User $userB): ?self
    {
        $aId = $userA->getKey();
        $bId = $userB->getKey();

        return static::query()
            ->where(function ($q) use ($aId, $bId) {
                $q->where('user_a_id', $aId)->where('user_b_id', $bId);
            })
            ->orWhere(function ($q) use ($aId, $bId) {
                $q->where('user_a_id', $bId)->where('user_b_id', $aId);
            })
            ->first();
    }

    /**
     * Find or create a chat room between two users.
     * The lower numeric ID is always user_a to respect the unique constraint.
     */
    public static function firstOrCreateBetween(User $userA, User $userB): self
    {
        $aId = min($userA->getKey(), $userB->getKey());
        $bId = max($userA->getKey(), $userB->getKey());

        return static::firstOrCreate(
            ['user_a_id' => $aId, 'user_b_id' => $bId],
        );
    }
}
