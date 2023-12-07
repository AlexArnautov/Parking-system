<?php

declare(strict_types=1);

namespace App\Vehicle\Domain\Factory;

use App\Vehicle\Domain\Entity\Car;
use App\Vehicle\Domain\Entity\Motorcycle;
use App\Vehicle\Domain\Entity\Van;
use Exception;

class RandomVehicleFactory
{
    /**
     * @throws Exception
     */
    public function createRandomVehicle(): Motorcycle|Car|Van
    {
        $randomNumber = random_int(1, 3);

        return match ($randomNumber) {
            1 => new Car(),
            2 => new Motorcycle(),
            3 => new Van(),
        };
    }

}
