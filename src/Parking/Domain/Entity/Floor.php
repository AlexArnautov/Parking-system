<?php

declare(strict_types=1);

namespace App\Parking\Domain\Entity;


use App\Parking\Domain\Enum\VehicleType;

final class Floor
{
    public const HALF_SPOT_CAPACITY = 0.5;
    private float $occupiedSpace = 0;

    public function __construct(
        private readonly float $capacity,
        private array $allowedVehicleTypes
    ) {
        $this->setAllowedVehicleTypes($allowedVehicleTypes);
    }

    public function getCapacity(): float
    {
        return $this->capacity;
    }

    public function addAllowedVehicleType(VehicleType $vehicleType): void
    {
        $this->allowedVehicleTypes[] = $vehicleType;
    }

    private function setAllowedVehicleTypes(array $allowedVehicleTypes): void
    {
        foreach ($allowedVehicleTypes as $allowedVehicleType) {
            $this->addAllowedVehicleType($allowedVehicleType);
        }
    }

    public function hasHalfOfParkingSpace(): bool
    {
        $fractionalPart = fmod($this->occupiedSpace, 1);
        return $fractionalPart === self::HALF_SPOT_CAPACITY;
    }

    public function isVehicleAllowed(Vehicle $vehicle): bool
    {
        if (in_array($vehicle->type, $this->allowedVehicleTypes, true)) {
            return true;
        }
        return false;
    }

    public function getOccupiedSpace(): float
    {
        return $this->occupiedSpace;
    }

    public function setOccupiedSpace(float $occupiedSpace): void
    {
        $this->occupiedSpace = $occupiedSpace;
    }


    public function getLeftCapacity(): float
    {
        return $this->capacity - $this->occupiedSpace;
    }
}