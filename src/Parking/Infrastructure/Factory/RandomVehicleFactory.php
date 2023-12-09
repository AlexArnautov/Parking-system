<?php

declare(strict_types=1);

namespace App\Parking\Infrastructure\Factory;

use App\Parking\Domain\Entity\Vehicle;
use App\Parking\Domain\Enum\VehicleType;
use Exception;

class RandomVehicleFactory
{
    /**
     * @throws Exception
     */
    public function createRandomVehicle(): Vehicle
    {
        $randomNumber = random_int(1, 3);

        return match ($randomNumber) {
            1 => new Vehicle(VehicleType::Car),
            2 => new Vehicle(VehicleType::Moto),
            3 => new Vehicle(VehicleType::Van),
        };
    }

}
