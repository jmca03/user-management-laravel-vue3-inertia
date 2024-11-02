<?php

namespace App\Enums;

enum UserDetailKey: string
{
    case FULL_NAME      = 'Full Name';
    case MIDDLE_INITIAL = 'Middle Initial';
    case AVATAR         = 'Avatar';
    case GENDER         = 'Gender';

    public static function modelAttribute(string $key): string
    {
        return match($key) {
          self::FULL_NAME->value => 'fullname',
          self::MIDDLE_INITIAL->value => 'middleinitial',
          self::AVATAR->value => 'avatar',
          self::GENDER->value => 'gender',
          default => null
        };
    }
}
