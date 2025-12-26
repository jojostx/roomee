<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFavorite
 */
class Favorite extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function favoriter()
    {
        return $this->belongsTo(User::class, 'favoriter_id');
    }

    public function favoritee()
    {
        return $this->belongsTo(User::class, 'favoritee_id');
    }
}
