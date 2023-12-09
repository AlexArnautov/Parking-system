<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Parking\Application\ParkingStrategy\ParkingStrategyFactory;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use App\Parking\Domain\Enum\VehicleType;
use App\Shared\Infrastructure\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()->defaults()->autowire()->public();
    $services->load('App\\', '../src/*');
    $services->set(Floor::class)->args([100.0, []]);
    $services->set(ParkingGarage::class)->args([[]]);
    $services->set(VehicleType::class);
    $services->set(ParkingSpotOccupancy::class);
    $services->set(ParkingStrategyFactory::class)->args([new Reference('service_container')]);

    $services->set(LoggerInterface::class)->factory([LoggerFactory::class, 'getLogger']);
    $services->alias(ContainerInterface::class, 'service_container');
};