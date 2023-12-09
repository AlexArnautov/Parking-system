<?php

declare(strict_types=1);

namespace App\Parking\Application\Service;


use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;

class FloorFinderService implements FloorFinderServiceInterface
{
    public function getAllowedFloors(ParkingGarage $parkingGarage, Vehicle $vehicle): array
    {
        $result = [];

        /** @var Floor $floor */
        foreach ($parkingGarage->getFloors() as $key => $floor) {
            if ($floor->isVehicleAllowed($vehicle)) {
                $result[$key] = $floor;
            }
        }
        return $result;
    }

    public function getRequiredParkingSpotKey(Floor $floor, ParkingSpotOccupancy $spotOccupancy, int $blockedNumber = null): ?int
    {
        /** @var ParkingSpot $parkingSpot */
        foreach ($floor->getParkingSpots() as $key => $parkingSpot) {
            if ($key === $blockedNumber) {
                continue;
            }
            if ($parkingSpot->getOccupancy() === $spotOccupancy) {
                return $key;
            }
        }

        return null;
    }
}