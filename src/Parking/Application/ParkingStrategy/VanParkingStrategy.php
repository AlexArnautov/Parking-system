<?php

namespace App\Parking\Application\ParkingStrategy;


use App\Parking\Application\Service\FloorFinderServiceInterface;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;


readonly class VanParkingStrategy implements ParkingStrategyInterface
{

    public function __construct(
        private FloorFinderServiceInterface $finderService
    ) {
    }

    public function park(ParkingGarage $parkingGarage, Vehicle $vehicle): bool
    {
        $allowedFloors = $this->finderService->getAllowedFloors($parkingGarage, $vehicle);

        if (empty($allowedFloors)) {
            return false;
        }

        /** @var Floor $floor */
        foreach ($allowedFloors as $floor) {
            $halfSpotNumber = $this->finderService->getRequiredParkingSpotKey(
                $floor,
                ParkingSpotOccupancy::Half
            );

            $fullSpotNumber = $this->finderService->getRequiredParkingSpotKey(
                $floor,
                ParkingSpotOccupancy::Free
            );

            if ($halfSpotNumber !== null && $fullSpotNumber !== null) {
                $floor->changeParkingSpot($halfSpotNumber, ParkingSpotOccupancy::Full);
                $floor->changeParkingSpot($fullSpotNumber, ParkingSpotOccupancy::Full);
                return true;
            }
        }

        /** @var Floor $floor */
        foreach ($allowedFloors as $floor) {
            $firstFreeSpotNumber = $this->finderService->getRequiredParkingSpotKey(
                $floor,
                ParkingSpotOccupancy::Free
            );

            if ($firstFreeSpotNumber === null) {
                return false;
            }

            $secondFreeSpotNumber = $this->finderService->getRequiredParkingSpotKey(
                $floor,
                ParkingSpotOccupancy::Free,
                $firstFreeSpotNumber
            );

            if ($secondFreeSpotNumber !== null) {
                $floor->changeParkingSpot($firstFreeSpotNumber, ParkingSpotOccupancy::Full);
                $floor->changeParkingSpot($secondFreeSpotNumber, ParkingSpotOccupancy::Half);
                return true;
            }
        }

        return false;
    }
}
