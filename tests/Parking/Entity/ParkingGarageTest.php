<?php


namespace Parking\Entity;

use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Enum\VehicleType;
use PHPUnit\Framework\TestCase;

class ParkingGarageTest extends TestCase
{
    public function testAddFloor(): void
    {
        $floor = new Floor([VehicleType::Van], []);
        $garage = new ParkingGarage([]);
        $garage->addFloor($floor);

        $this->assertSame([$floor], $garage->getFloors());
    }
}