<?php

declare(strict_types=1);

namespace App\Parking\Domain\Enum;

enum VehicleType: string
{
    case Moto = 'moto';
    case Car = 'car';
    case Van = 'van';
}
