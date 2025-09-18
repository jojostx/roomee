<?php
if (!function_exists('hasBlankElement')) {

  /**
   *  checks if at least one element in the arguments array ($args) is blank
   * 
   *  @param array $args arguments array
   *  @return bool
   */
  function hasAnyBlankElement(...$args): bool
  {
    return collect($args)->contains(function ($value) {
      return blank($value);
    });
  }
}
