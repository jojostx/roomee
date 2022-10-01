<?php

namespace App\Enums;

enum RequestType: string
{
  case SENT = 'sent';
  case RECIEVED = 'recieved';
}
