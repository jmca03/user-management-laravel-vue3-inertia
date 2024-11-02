<?php

namespace App\Enums;

enum UserPrefix: string
{
    case MR  = 'Mr';
    case MRS = 'Mrs';
    case MS  = 'Ms';

    /**
     * Get gender
     *
     * @param ?string $prefix
     *
     * @return string|null
     */
    public static function gender(?string $prefix): ?string
    {
        return match($prefix) {
          self::MR->value => 'Male',
          self::MRS->value, self::MS->value => 'Female',
          default => null,
        };
    }
}
