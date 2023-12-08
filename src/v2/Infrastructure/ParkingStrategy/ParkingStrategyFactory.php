<?php

namespace App\v2\Infrastructure\ParkingStrategy;

use App\v2\Domain\Entity\Vehicle\Van;
use App\v2\Domain\ParkingStrategyInterface;
use App\v2\Domain\VehicleInterface;

class ParkingStrategyFactory
{
    public function create(VehicleInterface $vehicle): ParkingStrategyInterface
    {
        $defaultStrategy = new DefaultParkingStrategy();

        if ($vehicle instanceof Van) {
            return new VanParkingStrategy($defaultStrategy);
        }

        return $defaultStrategy;
    }
}
