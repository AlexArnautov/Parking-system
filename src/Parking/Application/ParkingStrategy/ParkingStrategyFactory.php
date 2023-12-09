<?php

declare(strict_types=1);

namespace App\Parking\Application\ParkingStrategy;

use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\VehicleType;
use Symfony\Component\DependencyInjection\ContainerInterface;

readonly class ParkingStrategyFactory
{
    public function __construct(
        private ContainerInterface $parkingStrategyLocator,
    ) {
    }

    public function getStrategy(Vehicle $vehicle): object
    {
        return match ($vehicle->type) {
            VehicleType::Car => $this->parkingStrategyLocator->get(CarParkingStrategy::class),
            VehicleType::Moto => $this->parkingStrategyLocator->get(MotoParkingStrategy::class),
            VehicleType::Van => $this->parkingStrategyLocator->get(VanParkingStrategy::class),
        };
    }
}
