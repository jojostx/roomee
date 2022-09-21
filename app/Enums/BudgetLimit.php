<?php

namespace App\Enums;

enum BudgetLimit: int
{
  case MIN = 40000;
  case MAX = 300000;

  public static function toArray(): array
  {
    return [
      self::MIN->value => 'Minimum',
      self::MAX->value => 'Maximum',
    ];
  }

  public static function budgetRange(): array
  {
    return range(self::MIN->value, self::MAX->value, 20000);
  }

  public static function budgetRangeAssoc(): array
  {
    return collect(range(self::MIN->value, self::MAX->value, 20000))
      ->mapWithKeys(fn ($value) => [$value => number_format(floatval($value), 2)])
      ->toArray();
  }
}
