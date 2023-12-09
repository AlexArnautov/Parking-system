<?php

namespace Parking\Service;

use App\Parking\Application\Service\FloorFinderService;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use App\Parking\Domain\Enum\VehicleType;
use PHPUnit\Framework\TestCase;

class FloorFinderServiceTest extends TestCase
{
    public function testGetAllowedFloors(): void
    {
        $floors = [
            new Floor(
                allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car, VehicleType::Van],
                parkingSpots: []
            ),

            new Floor(
                allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car],
                parkingSpots: []
            ),

        ];

        $parkingGarage = new ParkingGarage($floors);
        $floorFinderService = new FloorFinderService();
        $vehicle = new Vehicle(VehicleType::Van);
        $allowedFloors = $floorFinderService->getAllowedFloors($parkingGarage, $vehicle);
        $this->assertCount(1, $allowedFloors);
        $this->assertEquals($floors[0], reset($allowedFloors));
    }

    public function testGetRequiredParkingSpotKey(): void
    {
        $parkingSpots = [
            new ParkingSpot(ParkingSpotOccupancy::Full),
            new ParkingSpot(ParkingSpotOccupancy::Half),
            new ParkingSpot(ParkingSpotOccupancy::Free),
            new ParkingSpot(ParkingSpotOccupancy::Free),
        ];

        $floor = new Floor(
            allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car],
            parkingSpots: $parkingSpots
        );

        $floorFinderService = new FloorFinderService();

        $halfSpotKey = $floorFinderService->getRequiredParkingSpotKey($floor, ParkingSpotOccupancy::Half);
        $freeSpotKey = $floorFinderService->getRequiredParkingSpotKey($floor, ParkingSpotOccupancy::Free);

        $this->assertEquals(1, $halfSpotKey);
        $this->assertEquals(2, $freeSpotKey);
    }
}