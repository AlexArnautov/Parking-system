<?php

namespace Parking\Entity;

use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    public function testSetOccupancy(): void
    {
        $spot = new ParkingSpot(ParkingSpotOccupancy::Free);
        $spot->setOccupancy(ParkingSpotOccupancy::Half);

        $this->assertEquals(ParkingSpotOccupancy::Half, $spot->getOccupancy());
    }
}