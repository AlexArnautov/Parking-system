<?php

declare(strict_types=1);

namespace App\ParkingGarage\Domain\Service;

use App\Floor\Domain\Service\FloorResolverServiceInterface;
use App\ParkingGarage\Domain\Entity\ParkingGarageInterface;
use App\Vehicle\Domain\Entity\VehicleInterface;

readonly class ParkingManagerService implements ParkingManagerServiceInterface
{
    public function __construct(
        private FloorResolverServiceInterface $floorResolver
    ) {
    }

    public function parkVehicle(ParkingGarageInterface $parkingGarage, VehicleInterface $vehicle): bool
    {
        $key = $this->floorResolver->resolveFloor($parkingGarage, $vehicle);

        if ($key === null) {
            return false;
        }

        $floor = $parkingGarage->getFloors()[$key];
        $vehicleSize = $vehicle->getSize();
        $floor->setOccupiedSpace($floor->getOccupiedSpace() + $vehicleSize);
        return true;
    }
}