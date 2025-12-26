<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBlocklist
 */
class Blocklist extends Model
{
    use HasFactory, BindsOnUuid, GeneratesUuid;

    protected $guarded = ['id'];

    protected $casts = [
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'blockee_id');
    }

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    public function blockee()
    {
        return $this->belongsTo(User::class, 'blockee_id');
    }
}
