<?php

namespace App\Enums;

enum OnUserAction: string
{
  case BLOCK = 'block';
  case REPORT = 'report';
}
