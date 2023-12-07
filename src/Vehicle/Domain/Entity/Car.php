<?php

declare(strict_types=1);

namespace App\Vehicle\Domain\Entity;

class Car implements VehicleInterface
{
    public const CAR_SIZE = 1;

    public function getSize(): float
    {
        return self::CAR_SIZE;
    }
}