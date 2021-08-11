<?php

namespace App\Models;

use App\Http\ModelSimilarity\UserSimilarity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

//implements MustVerifyEmail

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The default values of attributes.
     *
     * @var array
     */
    protected $attributes = [
        'profile_updated' => false,
    ];

    /**
     * The appended attributes.
     *
     * @var array
     */
    protected $appends = ['similarity_score'];

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
        'rooms',
        'min_budget',
        'max_budget',
        'course_level',
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

    //RELATIONSHIPS

    /**
     * The blocklist for the user.
     */
    public function blocklists()
    {
        return $this->belongsToMany(User::class, 'blocklists', 'blocker_id', 'blockee_id')
        ->withTimestamps()->orderByPivot('created_at', 'desc');
    }
    
    /**
     * The users who are currently blocking this users.
     */
    public function blockers()
    {
        return $this->belongsToMany(User::class, 'blocklists', 'blockee_id' ,'blocker_id')
        ->withTimestamps();
    }
  
    /**
     * The favorited users for a user.
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'favoriter_id', 'favoritee_id')
        ->withTimestamps()->orderByPivot('created_at', 'desc');
    }

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
        return $this->belongsToMany(Town::class)->withTimestamps();
    }

    /**
     * The reports that was made made by user.
     */
    public function reports()
    {
        return  $this->belongsToMany(Report::class, 'report_user', 'reporter_id', 'report_id')->withPivot('reportee_id')->withTimestamps();
    }
    
    //SCOPES//

    /**
     * Scope a query to only include users of the same gender.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Str  $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGender($query, $gender)
    {
       return $query->where('gender', $gender);
    }
    
    /**
     * Scope a query to only include users that attend the same school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Int  $school_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSchool($query, $school_id)
    {
       return $query->where('school_id', $school_id);
    }

    /**
     * Scope a query to only include users that attend the same school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Int  $school_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeUser($query, $user_id)
    {
       return $query->whereKeyNot($user_id);
    }

    //ACCESSORS

    public function getFullnameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getSimilarityScoreAttribute()
    {
        return (new UserSimilarity())->calculateUserSimilarityScore($this);
    }

    public function getAvatarPathAttribute()
    {
        return Storage::disk('avatars')->url($this->id.'\\'.$this->avatar)??'';
    }

    public function getCoverPhotoPathAttribute()
    {
        return Storage::disk('cover_photos')->url($this->id.'\\'.$this->cover_photo);
    }
}
