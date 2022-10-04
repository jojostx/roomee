<?php

namespace App\Repositories;

use App\Models\FaqCategory;

class FaqRepository
{
  public static function getFaqskeyedByCategories(): array
  {
    $faqCategories = FaqCategory::with('faqs')->get();

    return $faqCategories->mapWithKeys(function ($item) {
      return [$item['title'] => $item->faqs->mapWithKeys(function ($item) {
        return [$item['question'] => $item['answer']];
      })->toArray()];
    })->toArray();
  }
}