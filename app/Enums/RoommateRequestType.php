<?php

namespace App\Enums;

enum RoommateRequestType: string
{
  case SENT = 'sent';
  case RECIEVED = 'recieved';
}