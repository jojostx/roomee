<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    /**
     * The School that the town belongs to.
     */
    public function School()
    {
        return $this->belongsToMany(School::class);
    }

    /**
     * The users that the town belongs to.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
