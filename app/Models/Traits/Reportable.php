<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;

trait Reportable
{
  public function reportUser(User $user, array $report_ids)
  {
    return DB::table('report_user')
      ->insert(array_map(function ($item) use ($user) {
        $timestamp = now();

        return [
          'reporter_id' => $this->id,
          'reportee_id' => $user->id,
          'report_id' => $item,
          'created_at' => $timestamp,
          'updated_at' => $timestamp,
        ];
      }, $report_ids));
  }
}
