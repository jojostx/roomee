<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperHobby
 */
class Hobby extends Model
{
    use HasFactory, BindsOnUuid, GeneratesUuid;

    public bool $checked = false;

    /**
     * The users that belong to the hobby.
     */
    public function users(){
       return $this->belongsToMany(User::class, 'hobby_user')->withTimestamps();
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }
}
