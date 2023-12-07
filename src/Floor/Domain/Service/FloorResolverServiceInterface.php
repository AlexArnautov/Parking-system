<?php

declare(strict_types=1);

namespace App\Floor\Domain\Service;

use App\ParkingGarage\Domain\Entity\ParkingGarageInterface;
use App\Vehicle\Domain\Entity\VehicleInterface;

interface FloorResolverServiceInterface
{
    public function resolveFloor(ParkingGarageInterface $parkingGarage, VehicleInterface $vehicle): ?int;
}