<?php

namespace App\Enums;

enum RoommateRequestStatus: int
{
    case PENDING = 0;
    case ACCEPTED = 1;
    case DENIED = 2;
    case DELETED = 3;
}
