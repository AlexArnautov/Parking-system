<?php

declare(strict_types=1);

namespace App\Parking\Domain\Entity;

use App\Parking\Domain\Enum\VehicleType;

final readonly class Vehicle
{
    public function __construct(
        public VehicleType $type
    ) {
    }
}