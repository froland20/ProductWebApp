<?php

namespace App\UserBundle\Enum;

enum Gender: int
{
    case Male = 1;
    case Female = 2;
    case Other = 3;

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Man',
            self::Female => 'Woman',
            self::Other => 'Other',
        };
    }
}