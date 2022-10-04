<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSchool
 */
class School extends Model
{
    use HasFactory, BindsOnUuid, GeneratesUuid;

    protected $fillable = [
        'uuid',
        'name',
        'short_name',
        'state'
    ];

    /**
     * The courses that belongs to this school.
     */
    public function courses()
    {
        return  $this->belongsToMany(Course::class, 'course_school')->withTimestamps();
    }


    /**
     * The towns that belong to the school.
     */
    public function towns()
    {
        return $this->belongsToMany(Town::class)->withTimestamps();
    }

    /**
     * The users that belong to this school.
     */
    public function users()
    {
        return  $this->hasMany(User::class);
    }
}
