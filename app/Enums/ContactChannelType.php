<?php

namespace App\Enums;

use Illuminate\Validation\Rule;

enum ContactChannelType: string
{
  case EMAIL = 'email';
  case PHONENUMBER = 'phone_number';
  case WHATSAPP = 'whatsapp';
  case MESSENGER = 'facebook_messenger';
  case TELEGRAM = 'telegram';
  case SNAPCHAT = 'snapchat';
  case TWITTER = 'twitter';

  public function getValidationRule(ContactChannelType $type)
  {
    $rule = match ($type) {
      static::EMAIL => ''
    };

    return $rule;
  }
}
