<?php

namespace App\Models\Traits;

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
            'blocker_id' => $this->getKey(),
            'blockee_id' => $recipient->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($blocked) {
            UserBlocked::dispatch($this->getKey(), $recipient->getKey(), 'blocked');     
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
            UserBlocked::dispatch($this->getKey(), $recipient->getKey(), 'unblocked');            
        }

        return  $unblocked;
    }

    public function hasBlocked(Model $recipient): bool
    {
        $blocking = DB::table('blocklists')->where(
            [
                'blocker_id' => $this->getKey(),
                'blockee_id' => $recipient->getKey(),
            ]
        )->get('id');

        return $blocking->isNotEmpty();
    }

    public function isBlockedBy(Model $recipient): bool
    {
        return $recipient->hasBlocked($this);
    }
}
