<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCourse
 */
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
   * Get the course levels for the course.
   *
   * @return \Illuminate\Database\Eloquent\Casts\Attribute
   */
  protected function levels(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => static::getCourseLevels($this, true)
    )->shouldCache();
  }

  public static function getCourseLevels(?Course $course = NULL, bool $only_keys = false)
  {
    if (blank($course)) {
      return [];
    }

    $levels = collect(range(100, $course->max_level + 100, 100));

    if ($only_keys) {
      return $levels->toArray();
    }

    return $levels->mapWithKeys(
      function ($level) use ($levels) {
        if ($levels->first() == $level) {
          return [$level => 'Pre-Degree'];
        } elseif ($levels->get(1) == $level) {
          return [$level => '100 Level (Fresher)'];
        } elseif ($levels->last() == $level) {
          return [$level => 'Post Graduate'];
        } else {
          return [$level => ((int) $level - 100) . ' Level'];
        }
      }
    )->toArray();
  }
}
