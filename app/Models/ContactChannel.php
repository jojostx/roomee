<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\VirtualColumn\VirtualColumn;

/**
 * @mixin IdeHelperContactChannel
 */
class ContactChannel extends Model
{
    use HasFactory, VirtualColumn, BindsOnUuid, GeneratesUuid;

    public $guarded = [];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'uuid',
            'user_id',
            'type',
            'link',
            'is_enabled',
            'verified_at',
            'created_at',
            'updated_at',
        ];
    }

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the name of the column that stores additional data.
     */
    public static function getDataColumn(): string
    {
        return 'metadata';
    }

    /**
     * The User that the contact channel belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * checks if the channel is verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    /**
     * checks if the channel is unverified.
     *
     * @return bool
     */
    public function isUnverified()
    {
        return !$this->isVerified();
    }

    /**
     * Mark the given channel as verified.
     *
     * @return bool
     */
    public function markAsVerified()
    {
        return $this->forceFill([
            'verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Mark the given channel as enabled.
     *
     * @return bool
     */
    public function markAsEnabled()
    {
        return $this->forceFill([
            'is_enabled' => true,
        ])->save();
    }

    /**
     * Mark the given channel as disabled.
     *
     * @return bool
     */
    public function markAsDisabled()
    {
        return $this->forceFill([
            'is_enabled' => false,
        ])->save();
    }

    /**
     * Mark the given channel as the given flag.
     *
     * @return bool
     */
    public function markAs(bool $flag)
    {
        return $this->forceFill([
            'is_enabled' => $flag,
        ])->save();
    }
    /**
     * toggle is_enabled
     *
     * @return bool
     */
    public function toggleIsEnabled()
    {
        return $this->forceFill([
            'is_enabled' => !$this->is_enabled,
        ])->save();
    }
}
