<?php

declare(strict_types=1);

namespace App\Data;

use App\Exception\DriverNotFoundException;

interface DriverRepositoryInterface
{
    public function find(string $abbreviation): Driver;
}
