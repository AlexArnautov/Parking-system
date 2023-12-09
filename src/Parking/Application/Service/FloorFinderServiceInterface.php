<?php

declare(strict_types=1);

namespace App\Parking\Application\Service;


use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;

interface FloorFinderServiceInterface
{
    public function getAllowedFloors(ParkingGarage $parkingGarage, Vehicle $vehicle): array;

    public function getRequiredParkingSpotKey(
        Floor $floor,
        ParkingSpotOccupancy $spotOccupancy,
        ?int $blockedNumber = null
    ): ?int;
}