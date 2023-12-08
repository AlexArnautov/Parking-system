<?php

namespace App\v2\Infrastructure\ParkingStrategy;

use App\v2\Domain\Entity\GarageFloor;
use App\v2\Domain\ParkingStrategyInterface;
use App\v2\Domain\VehicleInterface;

class DefaultParkingStrategy implements ParkingStrategyInterface
{
    public function isAvailable(GarageFloor $floor, VehicleInterface $vehicle): bool
    {
        return $floor->getAvailableSize() >= $vehicle->getSize();
    }

    public function park(GarageFloor $floor, VehicleInterface $vehicle): void
    {
        // можно дополнительно проверить на наличие свободного места
        // ....

        $floor->takePlace($vehicle->getSize());
    }
}
