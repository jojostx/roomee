<?php

namespace App\Models\Traits;

use App\Enums\BlockStatus;
use App\Events\UserBlocked;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait Blockable
{
    public function block(Model $recipient): bool
    {
        if ($this->hasBlocked($recipient)) {
            return false;
        }

        $blocked = DB::table('blocklists')->insert([
            'uuid' => str()->uuid()->toString(),
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

    public function hasBlocked(Model $blockee): bool
    {
        return DB::table('blocklists')
            ->where([
                'blocker_id' => $this->getKey(),
                'blockee_id' => $blockee->getKey(),
            ])
            ->get('id')
            ->isNotEmpty();
    }

    public function isBlockedBy(Model $blocker): bool
    {
        return $blocker->hasBlocked($this);
    }
}
