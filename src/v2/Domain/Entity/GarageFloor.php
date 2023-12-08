<?php

namespace App\v2\Domain\Entity;

class GarageFloor
{
    public function __construct(
        public readonly int $floor,
        public readonly int $capacity,
        private float $busy,
        public readonly bool $vanAvailable,
    )
    {
    }

    public function takePlace(float $size): void
    {
        $this->busy += $size;
    }

    public function getAvailableSize(): float
    {
        return $this->capacity - $this->busy;
    }
}
