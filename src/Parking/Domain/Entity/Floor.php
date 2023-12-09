<?php

declare(strict_types=1);

namespace App\Parking\Domain\Entity;


use App\Parking\Domain\Enum\ParkingSpotOccupancy;
use App\Parking\Domain\Enum\VehicleType;

final class Floor
{
    private array $allowedVehicleTypes;
    private array $parkingSpots;

    public function __construct(
        array $allowedVehicleTypes,
        array $parkingSpots
    ) {
        $this->setAllowedVehicleTypes($allowedVehicleTypes);
        $this->setParkingSpots($parkingSpots);
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

    public function addParkingSpot(ParkingSpot $parkingSpot): void
    {
        $this->parkingSpots[] = $parkingSpot;
    }

    private function setParkingSpots(array $parkingSpots): void
    {
        foreach ($parkingSpots as $parkingSpot) {
            $this->addParkingSpot($parkingSpot);
        }
    }


    public function isVehicleAllowed(Vehicle $vehicle): bool
    {
        if (in_array($vehicle->type, $this->allowedVehicleTypes, true)) {
            return true;
        }
        return false;
    }

    public function getParkingSpots(): array
    {
        return $this->parkingSpots;
    }

    public function changeParkingSpot(int $number, ParkingSpotOccupancy $spotOccupancy): void
    {
        $this->parkingSpots[$number] = new ParkingSpot($spotOccupancy);
    }

    public function getLeftSpace(): float|int
    {
        $result = 0;
        /** @var ParkingSpot $parkingSpot */
        foreach ($this->parkingSpots as $parkingSpot) {
            $space = match ($parkingSpot->getOccupancy()) {
                ParkingSpotOccupancy::Half => 0.5,
                ParkingSpotOccupancy::Full => 0,
                ParkingSpotOccupancy::Free => 1,
            };
            $result += $space;
        }

        return $result;
    }
}