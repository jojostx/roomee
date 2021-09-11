<?php

namespace App\Models\Traits;

use App\Enums\RoommateRequestStatus as Status;
use App\Events\RoommateRequestUpdated;
use App\Models\RoommateRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait Requestable
{
    public function canSendRoommateRequest(Model $recipient): bool
    {
        if ($this->isBlockedBy($recipient) || $this->hasBlocked($recipient)) {
            return false;
        }

        if ($roommateRequest = $this->getRoommateRequest($recipient)) {
            // if previous friendship was Denied then let the user send fr
            if ($roommateRequest->status != Status::DENIED) {
                return false;
            }
        }

        return true;
    }

    public function sendRoommateRequest(Model $recipient): bool
    {
        if (!$this->canSendRoommateRequest($recipient)) {
            return false;
        }

        $id = RoommateRequest::getCompositeKey($this, $recipient);
        
        $inserted = DB::table('roommate_requests')->insert([
            'id' => $id,
            'status' => Status::PENDING,
            'requester_id' => $this->getKey(),
            'requestee_id' => $recipient->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RoommateRequestUpdated::dispatch($this->getKey(), $recipient->getKey(), 'sent'); 

        return $inserted;
    }
    
    public function deleteRoommateRequest(Model $recipient): bool
    {
        $id = RoommateRequest::getCompositeKey($this, $recipient);

        $deleted = (bool) DB::table('roommate_requests')->delete($id);

        RoommateRequestUpdated::dispatch(auth()->id(), $recipient->id, 'deleted'); 

        return $deleted;
    }
    
    public function findRoommateRequest(Model $recipient): Builder
    {
        return RoommateRequest::betweenModels($this, $recipient);
    }

    public function hasRoommateRequestFrom(Model $recipient): bool
    {
        return $this->findRoommateRequest($recipient)->whereSender($recipient)->whereStatus(Status::PENDING)->exists();
    }

    public function hasSentRoommateRequestTo(Model $recipient): bool
    {
        return RoommateRequest::whereRecipient($recipient)->whereSender($this)->whereStatus(Status::PENDING)->exists();
    }

    public function acceptRoommateRequest(Model $recipient): bool
    {
        $updated = $this->findRoommateRequest($recipient)->whereRecipient($this)->update([
            'status' => Status::ACCEPTED,
        ]);

        RoommateRequestUpdated::dispatch($this->getKey(), $recipient->getKey(), 'accepted'); 
      
        return (bool) $updated;
    }

    public function denyRoommateRequest(Model $recipient): bool
    {
        $updated = $this->findRoommateRequest($recipient)->whereRecipient($this)->update([
            'status' => Status::DENIED,
        ]);

        RoommateRequestUpdated::dispatch($this->getKey(), $recipient->getKey(), 'denied'); 
      
        return (bool) $updated;
    }

    public function isRoommateWith(Model $recipient): bool
    {
        return $this->findRoommateRequest($recipient)->where('status', Status::ACCEPTED)->exists();
    }
    
    // public function hasBlocked(Model $recipient): bool
    // {
    //     return $this->blocklists->contains($recipient);
    // }

    // public function isBlockedBy(Model $recipient): bool
    // {
    //     return $recipient->hasBlocked($this);
    // }
   
    public function getRoommateRequest(Model $recipient)
    {
        return $this->findRoommateRequest($recipient)->first();
    }

    public function getRoommateRequests(): Collection
    {
        return RoommateRequest::whereRecipient($this)->get();
    }

    public function getPendingRoommateRequests(): Collection
    {
        return $this->findRoommateRequests(Status::PENDING)->get();
    }

    public function getAcceptedRoommateRequests(): Collection
    {
        return $this->findRoommateRequests(Status::ACCEPTED)->get();
    }

    public function getDeniedRoommateRequests(): Collection
    {
        return $this->findRoommateRequests(Status::DENIED)->get();
    }
}