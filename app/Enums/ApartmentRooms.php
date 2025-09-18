<?php

namespace App\Enums;

enum ApartmentRooms: int
{
  case MIN_ROOMS = 1;
  case ONE_ROOM = 2;
  case TWO_ROOM = 3;
  case THREE_ROOM = 4;
  case MAX_ROOMS = 5;

  public static function toArray(): array
  {
    return range(self::MIN_ROOMS->value, self::MAX_ROOMS->value, 1);
  }

  public static function toAssocArray(): array
  {
    return [
      self::MIN_ROOMS->value => 'Self-contain',
      self::ONE_ROOM->value => 'One-bedroom Self-contain',
      self::TWO_ROOM->value => 'Two-bedroom self-contain',
      self::THREE_ROOM->value => 'Three-bedroom self-contain',
      self::MAX_ROOMS->value => 'others'
    ];
  }
}
