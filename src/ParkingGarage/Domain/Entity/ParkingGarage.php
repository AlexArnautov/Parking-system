<?php

declare(strict_types=1);

namespace App\ParkingGarage\Domain\Entity;

use App\Floor\Domain\Entity\FloorInterface;

class ParkingGarage implements ParkingGarageInterface
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

    public function addFloor(FloorInterface $floor): void
    {
        $this->floors[] = $floor;
    }
}