<?php

namespace App\Models;

use App\Enums\ContactChannelType;
use App\Enums\UserRole;
use App\Models\Traits\Blockable;
use App\Models\Traits\Requestable;
use App\Http\ModelSimilarity\canCalculateUserSimilarity;
use App\Models\Traits\Favoritable;
use App\Models\Traits\HasSettings;
use App\Models\Traits\MustVerifyNewEmail;
use App\Models\Traits\Reportable;
use App\Models\Traits\WithValidUsersQueryScopes;
use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Spatie\Onboard\Concerns\GetsOnboarded;
use Spatie\Onboard\Concerns\Onboardable;
use \Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements Onboardable, FilamentUser, HasName
{
  use GetsOnboarded,
    HasFactory,
    HasApiTokens,
    Notifiable,
    BindsOnUuid,
    GeneratesUuid,
    MustVerifyNewEmail,
    HasMergedRelationships,
    Blockable,
    Favoritable,
    Requestable,
    Reportable,
    WithValidUsersQueryScopes,
    canCalculateUserSimilarity,
    HasSettings;

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
    'role',
    'profile_updated',
    'gender',
    'avatar',
    'cover_photo',
    'bio',
    'rooms',
    'min_budget',
    'max_budget',
    'course_level',
    'settings'
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
    'profile_updated' => 'boolean',
    'settings' => 'array',
    'role' => UserRole::class,
  ];

  /**
   * Get the user's full name.
   */
  public function getFilamentName(): string
  {
    // Adjust based on your actual database structure
    if ($this->first_name && $this->last_name) {
      return "{$this->first_name} {$this->last_name}";
    }

    return $this->email ?? 'User';
  }

  public function canAccessPanel(Panel $panel): bool
  {
    $role = $this->role;

    if ($role instanceof UserRole) {
      return in_array($role, [UserRole::ADMIN, UserRole::STAFF], true);
    }

    return in_array($role, [UserRole::ADMIN->value, UserRole::STAFF->value], true);
  }

  public function isAdmin(): bool
  {
    return $this->role === UserRole::ADMIN;
  }

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
   * The reports that was made by user.
   */
  public function reports()
  {
    return  $this->belongsToMany(Report::class, 'report_user', 'reporter_id', 'report_id')
      ->withPivot('reportee_id')
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
   * The received roommate requests users for the user.
   */
  public function receivedRoommateRequests(): BelongsToMany
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

  /**
   * The contact channels of the user.
   */
  public function contactChannels(): HasMany
  {
    return $this->hasMany(ContactChannel::class, 'user_id');
  }

  public function allPotentialRoommates(): MergedRelation
  {
    return $this->mergedRelationWithModel(User::class, 'merged_roommate_requests_view');
  }

    // -------- SCOPES -------- //
  /**
   * The roommate requests for the user.
   */
  public function allRoommateRequests(): Builder
  {
    return RoommateRequest::query()
      ->where('sender_id', $this->getKey())
      ->orWhere('recipient_id', $this->getKey());
  }

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
   * @param  int  $school_id
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
   * @param  int  $user_id
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeExcludeUser($query, $user_id)
  {
    return $query->where('id', '<>', $user_id);
  }

  // -------- ACCESSORS -------- //
  public function getFullNameAttribute(): string
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function getAvatarPathAttribute(): string
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

  public function getCoverPhotoPathAttribute(): string
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

  public function getVerifiedContactChannels(): Collection|ContactChannel
  {
    $emailContactChannel = new ContactChannel;

    $emailContactChannel->fill([
      'id' => 0,
      'uuid' => $this->getAttribute('uuid'),
      'user_id' => $this->getKey(),
      'type' => ContactChannelType::EMAIL->value,
      'link' => 'mailto:' . $this->getAttribute('email'),
      'is_enabled' => true,
      'verified_at' => $this->getAttribute('email_verified_at'),
      'created_at' => $this->getAttribute('created_at'),
      'updated_at' => $this->getAttribute('updated_at'),
    ]);

    $contactChannels = $this
      ->contactChannels()
      ->where('is_enabled', true)
      ->whereNotNull('verified_at')
      ->get();

    return $contactChannels->prepend($emailContactChannel);
  }

  public function getContactChannelsByType(ContactChannelType|string $type)
  {
    $type = is_string($type) ? ContactChannelType::from($type) : $type;

    return $this
      ->contactChannels()
      ->getQuery()
      ->where('type', $type->value)
      ->get();
  }

  public function getLatestContactChannelByType(ContactChannelType|string $type): ?ContactChannel
  {
    $type = is_string($type) ? ContactChannelType::from($type) : $type;

    return $this
      ->contactChannels()
      ->getQuery()
      ->where('type', $type->value)
      ->latest()
      ->first();
  }

  public function getLatestContactChannels(): Collection
  {
    return $this
      ->contactChannels()
      ->getQuery()
      ->latest()
      ->get();
  }

  public function getSmsNumber(): ?string
  {
    return $this
      ->contactChannels()
      ->where('type', ContactChannelType::WHATSAPP->value)
      ->whereNotNull('verified_at')
      ->latest()
      ->value('link');
  }
}
