<?php

namespace App\Models;

use App\Enums\RoommateRequestStatus;
use App\Models\Traits\ManagesRoommateRequestStatus;
use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRoommateRequest
 */
class RoommateRequest extends Model
{
    use ManagesRoommateRequestStatus, HasFactory, BindsOnUuid, GeneratesUuid;

    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => RoommateRequestStatus::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRecipient($query, User $model)
    {
        return $query->where('recipient_id', $model->getKey());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSender($query, User $model)
    {
        return $query->where('sender_id', $model->getKey());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param Model $sender
     * @param Model $recipient
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenModels(Builder $query, User $sender, User $recipient)
    {
        $id = static::getCompositeKey($sender, $recipient);

        return $query->where('id', $id);
    }

    static function getCompositeKey(User $sender, User $recipient): string
    {
        $min = min([$sender->getKey(), $recipient->getKey()]);
        $max = max([$sender->getKey(), $recipient->getKey()]);

        return "$min" . "_" . "$max";
    }
}
