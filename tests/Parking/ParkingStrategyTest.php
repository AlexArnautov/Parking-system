<?php

namespace Parking;

use App\Parking\Application\ParkingStrategy\CarParkingStrategy;
use App\Parking\Application\ParkingStrategy\MotoParkingStrategy;
use App\Parking\Application\ParkingStrategy\ParkingStrategyFactory;
use App\Parking\Application\ParkingStrategy\VanParkingStrategy;
use App\Parking\Application\Service\FloorFinderService;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\ParkingSpot;
use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use App\Parking\Domain\Enum\VehicleType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParkingStrategyTest extends TestCase
{

    public function testPark()
    {
        $containerMock = $this->createMock(ContainerInterface::class);
        $floorFinderService = new FloorFinderService();
        $parkingStrategyFactory = new ParkingStrategyFactory($containerMock);
        $carParkingStrategy = new CarParkingStrategy($floorFinderService);
        $motoParkingStrategy = new MotoParkingStrategy($floorFinderService);
        $vanParkingStrategy = new VanParkingStrategy($floorFinderService);


        $parkingSpots = [
            new ParkingSpot(ParkingSpotOccupancy::Free),
            new ParkingSpot(ParkingSpotOccupancy::Free),
            new ParkingSpot(ParkingSpotOccupancy::Free),
            new ParkingSpot(ParkingSpotOccupancy::Free),
        ];

        $containerMock->method('get')
            ->willReturnCallback(
                function ($service) use ($carParkingStrategy, $motoParkingStrategy, $vanParkingStrategy) {
                    return match ($service) {
                        CarParkingStrategy::class => $carParkingStrategy,
                        MotoParkingStrategy::class => $motoParkingStrategy,
                        VanParkingStrategy::class => $vanParkingStrategy,
                        default => null,
                    };
                }
            );


        $floors = [
            new Floor(
                allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car, VehicleType::Van],
                parkingSpots: $parkingSpots
            ),

            new Floor(
                allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car],
                parkingSpots: $parkingSpots
            ),
        ];

        $parkingGarage = new ParkingGarage($floors);

        $vehicle = new Vehicle(VehicleType::Van);
        $parkingStrategy = $parkingStrategyFactory->getStrategy($vehicle);
        $result = $parkingStrategy->park($parkingGarage, $vehicle);
        $this->assertTrue($result);
        $this->assertEquals(2.5, $parkingGarage->getFloors()[0]->getLeftSpace());

        $vehicle = new Vehicle(VehicleType::Moto);
        $parkingStrategy = $parkingStrategyFactory->getStrategy($vehicle);
        $result = $parkingStrategy->park($parkingGarage, $vehicle);

        $this->assertTrue($result);
        $this->assertEquals(2, $parkingGarage->getFloors()[0]->getLeftSpace());

        $vehicle = new Vehicle(VehicleType::Car);
        $parkingStrategy = $parkingStrategyFactory->getStrategy($vehicle);
        $result = $parkingStrategy->park($parkingGarage, $vehicle);

        $this->assertTrue($result);
        $this->assertEquals(1, $parkingGarage->getFloors()[0]->getLeftSpace());

        $vehicle = new Vehicle(VehicleType::Van);
        $parkingStrategy = $parkingStrategyFactory->getStrategy($vehicle);
        $result = $parkingStrategy->park($parkingGarage, $vehicle);

        $this->assertFalse($result);
        $this->assertEquals(1, $parkingGarage->getFloors()[0]->getLeftSpace());
    }
}