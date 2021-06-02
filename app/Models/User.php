<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;


    /**
     * The default values of boolean attributes.
     *
     * @var array
     */
    protected $attributes = [
        'profile_updated' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'profile_updated',
        'gender',
        'avatar',
        'cover_photo',
        'bio',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'profile_updated' => 'boolean'
    ];

    /**
     * The hobbies that belong to the user.
     */
    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'hobby_user')->withTimestamps();
    }

    /**
     * The dislikes that belong to the user.
     */
    public function dislikes()
    {
        return $this->belongsToMany(Dislike::class, 'dislike_user')->withTimestamps();
    }

    /**
     * The Course that the user belongs to (the course that the user offers).
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * The School that the user belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * The towns that belong to the user.
     */
    public function towns()
    {
        return $this->belongsToMany(Town::class);
    }

    public function getAvatarPathAttribute()
    {
        return Storage::disk('avatars')->url($this->avatar);
    }
    
    public function getCoverPhotoPathAttribute()
    {
        return Storage::disk('avatars')->url($this->cover_photo);
    }
}
