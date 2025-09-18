<?php

namespace App\Models\Traits;

use App\Enums\BlockStatus;
use App\Events\UserBlocked;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait Blockable
{
    abstract public function isValidUser(User $recipient): bool;

    /**
     * block - adds a user to the model's blocklist
     * @param Model $recipient - the user to be blocked
     * @param bool $withScopedQuery - applies scope check for valid user
     */
    public function block(Model $recipient, bool $withScopedQuery = false): bool
    {
        if ($this->hasBlocked($recipient)) {
            return false;
        }

        if ($withScopedQuery && !$this->isValidUser($recipient)) {
            return false;
        }

        $blocked = DB::table('blocklists')->insert([
            'blocker_id' => $this->getKey(),
            'blockee_id' => $recipient->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($blocked) {
            UserBlocked::dispatch($this->getKey(), $recipient->getKey(), BlockStatus::BLOCKED);
        }

        return $blocked;
    }

    public function unblock(Model $recipient): bool
    {
        if (!$this->hasBlocked($recipient)) {
            return false;
        }

        $unblocked = (bool) DB::table('blocklists')->where([
            'blocker_id' => $this->getKey(),
            'blockee_id' => $recipient->getKey(),
        ])->delete();

        if ($unblocked) {
            UserBlocked::dispatch($this->getKey(), $recipient->getKey(), BlockStatus::UNBLOCKED);
        }

        return $unblocked;
    }

    /**
     * determines whether the blockee has been blocked by this user
     */
    public function hasBlocked(Model $blockee): bool
    {
        return DB::table('blocklists')
            ->where([
                'blocker_id' => $this->getKey(),
                'blockee_id' => $blockee->getKey(),
            ])
            ->exists();
    }

    /**
     * determines whether the blocker has blocked this user
     */
    public function isBlockedBy(Model $blocker): bool
    {
        return $blocker->hasBlocked($this);
    }
}
