<?php

namespace App\Interfaces;

interface UserServiceInterface
{
    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string;
}
