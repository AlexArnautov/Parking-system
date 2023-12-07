<?php

declare(strict_types=1);

namespace App\Floor\Domain\Entity;

use App\Vehicle\Domain\Entity\VehicleInterface;

interface FloorInterface
{
    public function getCapacity(): float;

    public function parkVehicle(VehicleInterface $vehicle): bool;

    public function addAllowedVehicleType(string $vehicleClass): void;

    public function removeAllowedVehicleType(string $vehicleClass);

    public function getAllowedVehicleTypes(): array;

    public function isVehicleAllowed(VehicleInterface $vehicle): bool;

    public function hasHalfOfParkingSpace(): bool;

    public function getLeftCapacity(): float;
}