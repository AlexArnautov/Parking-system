<?php

declare(strict_types=1);

namespace App\Parking\Application\ParkingStrategy;


use App\Parking\Application\Service\FloorFinderServiceInterface;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;


readonly class MotoParkingStrategy implements ParkingStrategyInterface
{
    public function __construct(
        private FloorFinderServiceInterface $floorFinderService
    ) {
    }

    public function park(ParkingGarage $parkingGarage, Vehicle $vehicle): bool
    {
        $allowedFloors = $this->floorFinderService->getAllowedFloors($parkingGarage, $vehicle);

        if (empty($allowedFloors)) {
            return false;
        }

        /** @var Floor $floor */
        foreach ($allowedFloors as $floor) {
            $spotNumber = $this->floorFinderService->getRequiredParkingSpotKey($floor, ParkingSpotOccupancy::Half);
            if ($spotNumber !== null) {
                $floor->changeParkingSpot($spotNumber, ParkingSpotOccupancy::Full);
                return true;
            }
        }

        /** @var Floor $floor */
        foreach ($allowedFloors as $floor) {
            $spotNumber = $this->floorFinderService->getRequiredParkingSpotKey($floor, ParkingSpotOccupancy::Free);
            if ($spotNumber !== null) {
                $floor->changeParkingSpot($spotNumber, ParkingSpotOccupancy::Half);
                return true;
            }
        }

        return false;
    }
}
