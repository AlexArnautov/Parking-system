<?php

declare(strict_types=1);

namespace App\Floor\Domain\Service;

use App\Floor\Domain\Entity\Floor;
use App\ParkingGarage\Domain\Entity\ParkingGarageInterface;
use App\Vehicle\Domain\Entity\VehicleInterface;

class FloorResolverService implements FloorResolverServiceInterface
{
    public function resolveFloor(ParkingGarageInterface $parkingGarage, VehicleInterface $vehicle): ?int
    {
        $possibleFloors = $this->getAllowedNotFullFloors($parkingGarage, $vehicle);

        if (empty($possibleFloors)) {
            return null;
        }

        $optimalFloors = $this->getOptimalFloors($possibleFloors, $vehicle);

        if (!empty($optimalFloors)) {
            return $this->getMostUnloadedFloorKey($optimalFloors);
        }

        return $this->getMostUnloadedFloorKey($possibleFloors);
    }

    private function getAllowedNotFullFloors(ParkingGarageInterface $parkingGarage, VehicleInterface $vehicle): array
    {
        $result = [];
        foreach ($parkingGarage->getFloors() as $key => $floor) {
            if ($floor->isVehicleAllowed($vehicle) && $floor->getLeftCapacity() >= $vehicle->getSize()) {
                $result[$key] = $floor;
            }
        }
        return $result;
    }

    /**
     * For most optimal load wi will use left half of parking slots
     * Example: we will put motorcycle to half of slot where already parked another motorcycle
     * @param array<Floor> $possibleFloors
     */
    private function getOptimalFloors(
        array $possibleFloors,
        VehicleInterface $vehicle
    ): array {
        $result = [];
        foreach ($possibleFloors as $key => $floor) {
            $isVehicleRequiredHalfOfOneParkingSpace = fmod($vehicle->getSize(), 1) === 0.5;
            if ($isVehicleRequiredHalfOfOneParkingSpace === true && $floor->hasHalfOfParkingSpace()) {
                $result[$key] = $floor;
            }
        }

        return $result;
    }

    /**
     * @param array<Floor> $floors
     */
    private function getMostUnloadedFloorKey(array $floors): int
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