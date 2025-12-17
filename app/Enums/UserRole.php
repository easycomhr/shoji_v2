<?php

namespace App\Enums;

enum UserRole: int
{
    case Admin      = 1;
    case Manager    = 2;
    case Customer   = 3;

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Administrator',
            self::Customer => 'Customer',
            self::Manager => 'Manager',
        };
    }

}
