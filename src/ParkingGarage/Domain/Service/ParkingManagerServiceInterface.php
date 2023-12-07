<?php

declare(strict_types=1);

namespace App\ParkingGarage\Domain\Service;

use App\ParkingGarage\Domain\Entity\ParkingGarageInterface;
use App\Vehicle\Domain\Entity\VehicleInterface;

interface ParkingManagerServiceInterface
{
    public function parkVehicle(ParkingGarageInterface $parkingGarage, VehicleInterface $vehicle): bool;
}