<?php

namespace App\v2\Domain\Entity\Vehicle;

use App\v2\Domain\VehicleInterface;


class Van implements VehicleInterface
{
    public function getSize(): float
    {
        return 1.5;
    }
}
