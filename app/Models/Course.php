<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * The schools that belong to this course (the schools that offers this course).
     */
    public function schools()
    {
        return  $this->belongsToMany(School::class, 'course_school')->withTimestamps();
    }

    /**
     * The users that belong to this course (the users that offer this course).
     */
    public function users()
    {
        return  $this->hasMany(User::class);
    }

    /**
     * Get the parking lot's qrcode svg.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function levels(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => static::getCourseLevels($this)
        )->shouldCache();
    }

    public static function getCourseLevels(?Course $course = NULL): array
    {
      $result = [];
  
      if (blank($course)) {
        return $result;
      }
  
      foreach ($levels = collect(range(100, $course->max_level + 100, 100)) as $value) {
        if ($levels->first() == $value) {
          $result[$value] = 'Pre-Degree';
        } elseif ($levels->get(1) == $value) {
          $result[$value] = '100 Level (Fresher)';
        } elseif ($levels->last() == $value) {
          $result[$value] = 'Post Graduate';
        } else {
          $result[$value] = ((int) $value - 100) . ' Level';
        }
      }
  
      return $result;
    }
}
