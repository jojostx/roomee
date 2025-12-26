<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case STAFF = 'staff';

    public static function labels(): array
    {
        return [
            self::USER->value => 'User',
            self::ADMIN->value => 'Admin',
            self::STAFF->value => 'Staff',
        ];
    }
}
