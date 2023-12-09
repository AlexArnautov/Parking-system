<?php

declare(strict_types=1);

namespace App\Parking\Application\Service;


use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;

class FloorFinderService implements FloorFinderServiceInterface
{

    public function getAllowedNotFullFloors(ParkingGarage $parkingGarage, Vehicle $vehicle): array
    {
        $result = [];

        /** @var Floor $floor */
        foreach ($parkingGarage->getFloors() as $key => $floor) {
            if ($floor->isVehicleAllowed($vehicle)
                && $floor->getLeftCapacity() >= $vehicle->type->getSize()) {
                $result[$key] = $floor;
            }
        }
        return $result;
    }

    /**
     * For most optimal load we will use left half of parking slots as soon as possible
     * Example: we will put motorcycle to half of slot where already parked another motorcycle
     * @param array<Floor> $floors
     */
    public function getFloorsWithHalfOfSpot(
        array $floors,
    ): array {
        $result = [];
        foreach ($floors as $key => $floor) {
            if ($floor->hasHalfOfParkingSpace()) {
                $result[$key] = $floor;
            }
        }

        return $result;
    }

    /**
     * @param array<Floor> $floors
     */
    public function getMostUnloadedFloorKey(array $floors): int
    {
        $maxLeftCapacity = PHP_FLOAT_MIN;
        $floorWithMaxLeftCapacity = null;

        foreach ($floors as $key => $floor) {
            $leftCapacity = $floor->getLeftCapacity();
            if ($leftCapacity > $maxLeftCapacity) {
                $maxLeftCapacity = $leftCapacity;
                $floorWithMaxLeftCapacity = $key;
            }
        }

        return $floorWithMaxLeftCapacity;
    }
}