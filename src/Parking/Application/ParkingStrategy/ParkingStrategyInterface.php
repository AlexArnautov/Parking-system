<?php

declare(strict_types=1);

namespace App\Parking\Application\ParkingStrategy;


use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Entity\Vehicle;

interface ParkingStrategyInterface
{
    public function park(ParkingGarage $parkingGarage, Vehicle $vehicle): bool;
}
