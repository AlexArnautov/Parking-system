<?php

declare(strict_types=1);

namespace App\Parking\Domain\Enum;


enum ParkingSpotOccupancy: string
{
    case Free = 'free';
    case Half = 'half';
    case Full = 'full';
}
