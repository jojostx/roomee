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

    /**
     * The User that the contact channel belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
