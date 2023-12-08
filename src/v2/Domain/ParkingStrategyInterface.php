<?php

namespace App\v2\Domain;


use App\v2\Domain\Entity\GarageFloor;

interface ParkingStrategyInterface
{
    public function isAvailable(GarageFloor $floor, VehicleInterface $vehicle): bool;

    public function park(GarageFloor $floor, VehicleInterface $vehicle): void;
}
