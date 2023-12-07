<?php

declare(strict_types=1);

namespace App\Vehicle\Domain\Entity;

class Motorcycle implements VehicleInterface
{
    public const MOTORCYCLE_SIZE = 0.5;

    public function getSize(): float
    {
        return self::MOTORCYCLE_SIZE;
    }
}