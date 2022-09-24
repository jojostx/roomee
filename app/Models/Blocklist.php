<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBlocklist
 */
class Blocklist extends Model
{
    use HasFactory;

    protected $casts = [

    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'blockee_id');
    }
}
