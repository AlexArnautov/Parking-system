<?php

declare(strict_types=1);

namespace App\Parking\Infrastructure\Factory;

use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;


class EmptyParkingSpotsFactory
{
    /**
     * @return array<ParkingSpot>
     */
    public function generateParkingSpots(int $amount): array
    {
        $result = [];
        for ($i = 1; $i <= $amount; $i++) {
            $result[] = new ParkingSpot(ParkingSpotOccupancy::Free);
        }
        return $result;
    }

}
