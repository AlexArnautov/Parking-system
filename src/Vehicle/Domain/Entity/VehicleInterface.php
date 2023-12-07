<?php

declare(strict_types=1);

namespace App\Vehicle\Domain\Entity;

interface VehicleInterface
{
    public function getSize(): float;
}