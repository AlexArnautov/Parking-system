<?php

declare(strict_types=1);

namespace App\Parking\Domain\Enum;

enum VehicleType: string
{
    case Moto = 'moto';
    case Car = 'car';
    case Van = 'van';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getSize(): float
    {
        return match ($this) {
            self::Car => 1,
            self::Moto => 0.5,
            self::Van => 1.5,
        };
    }
}
