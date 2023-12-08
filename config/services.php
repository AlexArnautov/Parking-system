<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Floor\Domain\Entity\Floor;
use App\ParkingGarage\Domain\Entity\ParkingGarage;
use App\Shared\Infrastructure\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()->defaults()->autowire()->public();
    $services->load('App\\', '../src/*')
        ->exclude('../src/v2/Domain/Entity/*')
    ;
    $services->set(Floor::class)->args([100.0, []]);
    $services->set(ParkingGarage::class)->args([[]]);
    $services->set(LoggerInterface::class)->factory([LoggerFactory::class, 'getLogger']);
};
