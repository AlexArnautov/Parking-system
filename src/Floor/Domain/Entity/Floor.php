<?php

declare(strict_types=1);

namespace App\Floor\Domain\Entity;

use App\Floor\Domain\Exception\WrongVehicleTypeException;
use App\Vehicle\Domain\Entity\VehicleInterface;

class Floor implements FloorInterface
{
    private float $occupiedSpace = 0;
    private array $allowedVehicleTypes;

    /**
     * @throws WrongVehicleTypeException
     */
    public function __construct(
        private readonly float $capacity,
        array $allowedVehicleTypes
    ) {
        $this->setAllowedVehicleTypes($allowedVehicleTypes);
    }

    /**
     * @throws WrongVehicleTypeException
     */
    public static function create(float $capacity, array $allowedVehicleTypes): self
    {
        return new self($capacity, $allowedVehicleTypes);
    }

    public function getCapacity(): float
    {
        return $this->capacity;
    }

    public function parkVehicle(VehicleInterface $vehicle): bool
    {
        $vehicleSize = $vehicle->getSize();

        if ($this->occupiedSpace + $vehicleSize <= $this->capacity) {
            $this->occupiedSpace += $vehicleSize;
            return true;
        }

        return false;
    }

    /**
     * @throws WrongVehicleTypeException
     */
    public function addAllowedVehicleType(string $vehicleClass): void
    {
        if (class_exists($vehicleClass)
            && is_subclass_of($vehicleClass, VehicleInterface::class)) {
            $this->allowedVehicleTypes[$vehicleClass] = $vehicleClass;
        } else {
            throw new WrongVehicleTypeException();
        }
    }

    public function removeAllowedVehicleType(string $vehicleClass): void
    {
        if (array_key_exists($vehicleClass, $this->allowedVehicleTypes)) {
            unset($this->allowedVehicleTypes[$vehicleClass]);
        }
    }

    public function getAllowedVehicleTypes(): array
    {
        return $this->allowedVehicleTypes;
    }

    /**
     * @throws WrongVehicleTypeException
     */
    private function setAllowedVehicleTypes(array $allowedVehicleTypes): void
    {
        foreach ($allowedVehicleTypes as $allowedVehicleType) {
            $this->addAllowedVehicleType($allowedVehicleType);
        }
    }

    public function hasHalfOfParkingSpace(): bool
    {
        $fractionalPart = fmod($this->occupiedSpace, 1);
        return $fractionalPart === 0.5;
    }

    public function isVehicleAllowed(VehicleInterface $vehicle): bool
    {
        foreach ($this->allowedVehicleTypes as $allowedType) {
            if ($vehicle instanceof $allowedType) {
                return true;
            }
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
