<?php

declare(strict_types=1);

namespace App\ParkingGarage\Domain\Entity;

use App\Floor\Domain\Entity\Floor;
use App\Floor\Domain\Entity\FloorInterface;

interface ParkingGarageInterface
{
    /**
     * @return array<Floor>
     */
    public function getFloors(): array;

    public function setFloors(array $floors): void;

    public function addFloor(FloorInterface $floor): void;
}