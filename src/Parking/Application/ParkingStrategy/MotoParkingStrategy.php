<?php

namespace App\Parking\Application\ParkingStrategy;


use App\Parking\Application\Service\FloorFinderServiceInterface;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;


readonly class MotoParkingStrategy implements ParkingStrategyInterface
{
    public function __construct(
        private FloorFinderServiceInterface $floorFinderService
    ) {
    }

    public function park(ParkingGarage $parkingGarage, Vehicle $vehicle): bool
    {
        $possibleFloors = $this->floorFinderService->getAllowedNotFullFloors($parkingGarage, $vehicle);

        if (empty($possibleFloors)) {
            return false;
        }

        $optimalFloors = $this->floorFinderService->getFloorsWithHalfOfSpot($possibleFloors);
        if (!empty($optimalFloors)) {
            $possibleFloors = $optimalFloors;
        }

        $floorNumber = $this->floorFinderService->getMostUnloadedFloorKey($possibleFloors);
        $floor = $parkingGarage->getFloors()[$floorNumber];
        $floor->setOccupiedSpace($floor->getOccupiedSpace() + $vehicle->type->getSize());
        return true;
    }
}
