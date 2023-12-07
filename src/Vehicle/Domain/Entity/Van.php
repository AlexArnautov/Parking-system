<?php

declare(strict_types=1);

namespace App\Vehicle\Domain\Entity;

class Van implements VehicleInterface
{
    public const VAN_SIZE = 1.5;

    public function getSize(): float
    {
        return self::VAN_SIZE;
    }
}