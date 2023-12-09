<?php


namespace Parking\Entity;

use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use App\Parking\Domain\Enum\VehicleType;
use PHPUnit\Framework\TestCase;

final class FloorTest extends TestCase
{

    public function testGetLeftSpaceTest(): void
    {
        $parkingSpots = [
            new ParkingSpot(ParkingSpotOccupancy::Half),
            new ParkingSpot(ParkingSpotOccupancy::Full),
            new ParkingSpot(ParkingSpotOccupancy::Free),
        ];

        $floor = new Floor([], $parkingSpots);
        $leftSpace = $floor->getLeftSpace();

        $this->assertEquals(1.5, $leftSpace);
    }

    public function testIsVehicleAllowed(): void
    {
        $allowedVehicleTypes = [VehicleType::Moto, VehicleType::Car];
        $parkingSpots = [];

        $floor = new Floor($allowedVehicleTypes, $parkingSpots);

        $allowedCar = new Vehicle(VehicleType::Car);
        $allowedMotorcycle = new Vehicle(VehicleType::Moto);
        $notAllowedTruck = new Vehicle(VehicleType::Van);

        $this->assertTrue($floor->isVehicleAllowed($allowedCar));
        $this->assertTrue($floor->isVehicleAllowed($allowedMotorcycle));
        $this->assertFalse($floor->isVehicleAllowed($notAllowedTruck));
    }

    public function testChangeParkingSpot(): void
    {
        // Arrange
        $allowedVehicleTypes = [VehicleType::Car];
        $initialOccupancy = ParkingSpotOccupancy::Free;
        $parkingSpots = [new ParkingSpot($initialOccupancy)];

        $floor = new Floor($allowedVehicleTypes, $parkingSpots);

        $newSpotNumber = 0;
        $newOccupancy = ParkingSpotOccupancy::Half;

        $floor->changeParkingSpot($newSpotNumber, $newOccupancy);

        $updatedParkingSpots = $floor->getParkingSpots();
        $updatedSpot = $updatedParkingSpots[$newSpotNumber];

        $this->assertInstanceOf(ParkingSpot::class, $updatedSpot);
        $this->assertEquals($newOccupancy, $updatedSpot->getOccupancy());
    }
}
