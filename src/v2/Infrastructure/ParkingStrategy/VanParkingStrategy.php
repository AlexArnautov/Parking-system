<?php

namespace App\v2\Infrastructure\ParkingStrategy;

use App\v2\Domain\Entity\GarageFloor;
use App\v2\Domain\ParkingStrategyInterface;
use App\v2\Domain\VehicleInterface;

readonly class VanParkingStrategy implements ParkingStrategyInterface
{

    public function __construct(
        private DefaultParkingStrategy $defaultStrategy,
    )
    {
    }

    public function isAvailable(GarageFloor $floor, VehicleInterface $vehicle): bool
    {
        if (!$floor->vanAvailable) {
            return false;
        }

        return $this->defaultStrategy->isAvailable($floor, $vehicle);
    }

    public function park(GarageFloor $floor, VehicleInterface $vehicle): void
    {
        $this->defaultStrategy->park($floor, $vehicle);
    }
}
