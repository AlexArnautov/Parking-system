<?php

namespace App\Parking\Application\ParkingStrategy;


use App\Parking\Application\Service\FloorFinderServiceInterface;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;


readonly class CarParkingStrategy implements ParkingStrategyInterface
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

        $floorNumber = $this->floorFinderService->getMostUnloadedFloorKey($possibleFloors);
        /** @var Floor $floor */
        $floor = $parkingGarage->getFloors()[$floorNumber];
        $floor->setOccupiedSpace($floor->getOccupiedSpace() + $vehicle->type->getSize());
        return true;
    }
}
