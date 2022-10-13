<?php

namespace App\Models;

use App\Models\Traits\Blockable;
use App\Models\Traits\Requestable;
use App\Http\ModelSimilarity\canCalculateUserSimilarity;
use App\Models\Traits\Favoritable;
use App\Models\Traits\Reportable;
use App\Models\Traits\WithValidUsersQueryScopes;
use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use \Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory,
        HasApiTokens,
        Notifiable,
        BindsOnUuid,
        GeneratesUuid,
        HasMergedRelationships,
        Blockable,
        Favoritable,
        Requestable,
        Reportable,
        WithValidUsersQueryScopes,
        canCalculateUserSimilarity;


    /**
     * The default values of attributes.
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
        'uuid',
        'first_name',
        'last_name',
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

    // -------- RELATIONSHIPS -------- //
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
        return $this->belongsToMany(Dislike::class, 'dislike_user', 'user_id', 'dislike_id')->withTimestamps();
    }

    /**
     * The towns that belong to the user.
     */
    public function towns()
    {
        return $this->belongsToMany(Town::class)->withTimestamps();
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
     * The reports that was made by user.
     */
    public function reports()
    {
        return  $this->belongsToMany(Report::class, 'report_user', 'reporter_id', 'report_id')->withPivot('reportee_id')->withTimestamps();
    }

    /**
     * The blocklist for the user.
     */
    public function blocklists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocklists', 'blocker_id', 'blockee_id')
            ->withTimestamps()->orderByPivot('created_at', 'desc');
    }

    /**
     * The users who are currently blocking this user.
     */
    public function blockers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocklists', 'blockee_id', 'blocker_id')
            ->withTimestamps();
    }

    /**
     * The recieved roommate requests users for the user.
     */
    public function recievedRoommateRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'roommate_requests', 'recipient_id', 'sender_id')
            ->withPivot('status')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    /**
     * The requests sent by the user.
     */
    public function sentRoommateRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'roommate_requests', 'sender_id', 'recipient_id')
            ->withPivot('status')
            ->withTimestamps()
            ->orderByPivot('created_at', 'desc');
    }

    public function allPotentialRoommates(): MergedRelation
    {
        return $this->mergedRelationWithModel(User::class, 'merged_roommate_requests_view');
    }

    /**
     * The roommate requests for the user.
     */
    public function allRoommateRequests(): Builder
    {
        return RoommateRequest::query()
            ->where('sender_id', $this->getKey())
            ->orWhere('recipient_id', $this->getKey());
    }

    // -------- SCOPES -------- //
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
     * Scope a query to only exclude the currently authenticated user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Int  $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeUser($query, $user_id)
    {
        return $query->where('id', '<>', $user_id);
    }

    // -------- ACCESSORS -------- //
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAvatarPathAttribute()
    {
        $avatar = asset('images/avatar_placeholder.png');

        if (filled($this->avatar) && Storage::disk('avatars')->exists($this->avatar)) {
            try {
                $avatar = Storage::disk('avatars')->url($this->avatar);
            } catch (\RuntimeException $th) {
            }
        }

        return $avatar;
    }

    public function getCoverPhotoPathAttribute()
    {
        $cover_photo = asset('images/cover_placeholder.png');

        if (filled($this->cover_photo) && Storage::disk('cover_photos')->exists($this->cover_photo)) {
            try {
                $cover_photo = Storage::disk('cover_photos')->url($this->cover_photo);
            } catch (\RuntimeException $th) {
            }
        }

        return $cover_photo;
    }
}
