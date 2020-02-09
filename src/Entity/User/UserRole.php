<?php
declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Value;

class UserRole extends Value
{
    public const GUEST = 1;
    public const ADMIN = 2;

    public static function translationOfAll(): array
    {
        return [
            self::GUEST => 'Guest',
            self::ADMIN => 'Administrator',
        ];
    }
}