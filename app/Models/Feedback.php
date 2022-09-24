<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFeedback
 */
class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback',
    ];
}
