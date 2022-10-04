<?php

namespace App\Models;

use Dyrynda\Database\Support\BindsOnUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFaqCategory
 */
class FaqCategory extends Model
{
    use HasFactory, BindsOnUuid, GeneratesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'title',
    ];

    /**
     * The faqs for the category.
     */
    public function faqs()
    {
        return  $this->hasMany(Faq::class, 'faq_category_id');
    }
}
