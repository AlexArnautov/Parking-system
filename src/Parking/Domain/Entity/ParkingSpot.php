<?php

declare(strict_types=1);

namespace App\Parking\Domain\Entity;


use App\Parking\Domain\Enum\ParkingSpotOccupancy;

final class ParkingSpot
{
    public function __construct(
        private ParkingSpotOccupancy $occupancy,
    ) {
    }

    public function getOccupancy(): ParkingSpotOccupancy
    {
        return $this->occupancy;
    }

    public function setOccupancy(ParkingSpotOccupancy $occupancy): void
    {
        $this->occupancy = $occupancy;
    }
}





