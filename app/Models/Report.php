<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The users that made a given type of report.
     */
    public function users()
    {
        return  $this->belongsToMany(User::class, 'report_user', 'report_id', 'reporter_id')->withPivot('reportee_id')->withTimestamps();
    }

}
