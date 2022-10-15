<?php

namespace App\Models\Traits;

use App\Enums\RoommateRequestStatus;
use Illuminate\Database\Eloquent\Builder;

trait ManagesRoommateRequestStatus
{
  /**
   * Checks if the roommate request is pending.
   */
  public function isPending(): bool
  {
    return $this->status == RoommateRequestStatus::PENDING;
  }

  /**
   * Checks if the roommate request has been accepted.
   */
  public function isAccepted(): bool
  {
    return $this->status == RoommateRequestStatus::ACCEPTED;
  }

  /**
   * Checks if the roommate request is DENIED.
   */
  public function isDenied(): bool
  {
    return $this->status == RoommateRequestStatus::DENIED;
  }

  /**
   * Scope a query to only query RoommateRequest of the type indicated by the $status parameter
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @param \App\Enums\Models\RoommateRequestStatus $type
   * @throws \ValueError
   * 
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeStatus(Builder $query, string|RoommateRequestStatus $status = '')
  {
    $status = is_string($status) ? RoommateRequestStatus::tryfrom($status) : $status;

    return $query->where('status', $status->value);
  }

  /**
   * Scope a query to only include DENIED roommate request.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeDenied(Builder $query)
  {
    return $query->where('status', RoommateRequestStatus::DENIED->value);
  }

  /**
   * Scope a query to only include pending roommate request.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePending(Builder $query)
  {
    return $query->where('status', RoommateRequestStatus::PENDING->value);
  }

  /**
   * Scope a query to only include accepted roommate request.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeAccepted(Builder $query)
  {
    return $query->where('status', RoommateRequestStatus::ACCEPTED->value);
  }
}
