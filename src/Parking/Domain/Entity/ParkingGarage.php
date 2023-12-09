<?php

declare(strict_types=1);

namespace App\Parking\Domain\Entity;

final class ParkingGarage
{
    private array $floors;

    public function __construct(array $floors)
    {
        $this->setFloors($floors);
    }

    public function getFloors(): array
    {
        return $this->floors;
    }

    public function setFloors(array $floors): void
    {
        foreach ($floors as $floor) {
            $this->addFloor($floor);
        }
    }

    public function addFloor(Floor $floor): void
    {
        $this->floors[] = $floor;
    }
}