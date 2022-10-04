<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDislike
 */
class Dislike extends Model
{
    use HasFactory, BindsOnUuid, GeneratesUuid;

    public bool $checked = false;
    /**
     * @var mixed
     */

    /**
     * The users that belong to the dislike.
     */
    public function users()
    {
        return  $this->belongsToMany(User::class, 'dislike_user')->withTimestamps();
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }
}
