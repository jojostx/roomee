<?php

namespace App\Enums;

enum ContactChannelType: string
{
  case WHATSAPP = 'whatsapp';
  case FACEBOOK = 'facebook';
  case INSTAGRAM = 'instagram';
  case TWITTER = 'twitter';
  case EMAIL = 'email';
}
