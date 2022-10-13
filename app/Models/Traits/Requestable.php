<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\RoommateRequest;
use App\Events\RoommateRequestUpdated;
use App\Enums\RoommateRequestStatus as Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Requestable
{
    use Blockable;

    /** query builders and scopes */
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $sender
     * @param User $recipient
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenModels(Builder $query, User $sender, User $recipient)
    {
        $id = static::getCompositeKey($sender, $recipient);

        return $query->where('id', $id);
    }


    /** checks */
    public function canSendRoommateRequestTo(User $recipient): bool
    {
        if (!$this->isValidNonBlockingUser($recipient)) {
           return false;
        }

        if ($this->isBlockedBy($recipient) || $this->hasBlocked($recipient)) {
            return false;
        }

        return !RoommateRequest::query()
            ->where('status', Status::DENIED->value)
            ->betweenModels($this, $recipient)
            ->exists();
    }

    public function hasPendingRoommateRequestFrom(User $sender): bool
    {
        return RoommateRequest::whereSender($sender)
            ->whereRecipient($this)
            ->where('status', Status::PENDING->value)
            ->exists();
    }

    public function hasPendingSentRoommateRequestTo(User $recipient): bool
    {
        return RoommateRequest::whereSender($this)
            ->whereRecipient($recipient)
            ->where('status', Status::PENDING->value)
            ->exists();
    }

    public function isRoommateWith(User $recipient): bool
    {
        $id = RoommateRequest::getCompositeKey($this, $recipient);

        return DB::table('roommate_requests')
            ->where('id', $id)
            ->where('status', Status::ACCEPTED->value)
            ->exists();
    }

    public function hasEitherSentOrRecievedRoommateRequest(User $recipient): bool
    {
        return RoommateRequest::query()->betweenModels($this, $recipient)->exists();
    }

    public function hasNeitherSentNorRecievedRoommateRequest(User $recipient): bool
    {
        return !$this->hasEitherSentOrRecievedRoommateRequest($recipient);
    }


    /** getters */
    public function getRoommateRequest(User $recipient)
    {
        return RoommateRequest::query()->betweenModels($this, $recipient)->first();
    }

    public function getRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereRecipient($this)
            ->orWhere(function ($query) {
                $query->whereSender($this);
            })
            ->get();
    }

    public function getSentRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereSender($this)
            ->get();
    }

    public function getRecievedRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereRecipient($this)
            ->get();
    }

    public function getPendingSentRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereStatus(Status::PENDING->value)
            ->whereSender($this)
            ->get();
    }

    public function getAcceptedSentRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereStatus(Status::ACCEPTED->value)
            ->whereSender($this)
            ->get();
    }

    public function getDeniedSentRoommateRequests(): Collection
    {
        return RoommateRequest::query()
            ->whereStatus(Status::DENIED->value)
            ->whereSender($this)
            ->get();
    }


    /** actions */
    public function sendRoommateRequest(User $recipient): bool
    {
        if (!$this->canSendRoommateRequestTo($recipient)) {
            return false;
        }

        $id = RoommateRequest::getCompositeKey($this, $recipient);

        $inserted = DB::table('roommate_requests')
            ->insert([
                'id' => $id,
                'uuid' => str()->uuid()->toString(),
                'status' => Status::PENDING->value,
                'sender_id' => $this->getKey(),
                'recipient_id' => $recipient->getKey(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        RoommateRequestUpdated::dispatch($this->getKey(), $recipient->getKey(), Status::PENDING);

        return $inserted;
    }

    public function deleteRoommateRequest(User $recipient): bool
    {
        $deleted = (bool) RoommateRequest::query()
            ->whereSender($this)
            ->whereRecipient($recipient)
            ->delete();

        RoommateRequestUpdated::dispatch(auth()->id(), $recipient->id, Status::DELETED);

        return $deleted;
    }

    public function acceptRoommateRequest(User $sender): bool
    {
        $updated = (bool) RoommateRequest::query()
            ->whereSender($sender)
            ->whereRecipient($this)
            ->update([
                'status' => Status::ACCEPTED->value,
            ]);

        RoommateRequestUpdated::dispatch($sender->getKey(), $this->getKey(), Status::ACCEPTED);

        return $updated;
    }

    public function denyRoommateRequest(User $sender): bool
    {
        $updated = (bool) RoommateRequest::query()
            ->whereSender($sender)
            ->whereRecipient($this)
            ->update([
                'status' => Status::DENIED->value,
            ]);

        RoommateRequestUpdated::dispatch($sender->getKey(), $this->getKey(), Status::DENIED);

        return $updated;
    }

    /** helpers */
    static function getCompositeKey(User $sender, User $recipient): string
    {
        $min = min([$sender->getKey(), $recipient->getKey()]);
        $max = max([$sender->getKey(), $recipient->getKey()]);

        return "$min" . "_" . "$max";
    }
}
