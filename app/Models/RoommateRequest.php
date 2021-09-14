<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoommateRequest extends Model
{
    use HasFactory;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'requestee_id');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereRecipient($query, User $model)
    {
        return $query->where('requestee_id', $model->getKey());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereSender($query, User $model)
    {
        return $query->where('requester_id', $model->getKey());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param Model $sender
     * @param Model $recipient
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenModels(Builder $query, User $sender, User $recipient)
    {
        $id = $this->getCompositeKey($sender, $recipient);

        return $query->where('id', $id);

    }

    static function getCompositeKey(User $sender, User $recipient): string
    {
        $min = min([$sender->getKey(), $recipient->getKey()]);
        $max = max([$sender->getKey(), $recipient->getKey()]);

        return "$min"."_"."$max";
    }               
}
