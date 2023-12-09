<?php

declare(strict_types=1);

namespace App\Parking\Application\Service;


use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;

interface FloorFinderServiceInterface
{
    public function getAllowedNotFullFloors(ParkingGarage $parkingGarage, Vehicle $vehicle): array;

    /**
     * @param array<Floor> $floors
     */
    public function getFloorsWithHalfOfSpot(array $floors): array;

    /**
     * @param array<Floor> $floors
     */
    public function getMostUnloadedFloorKey(array $floors): int;
}