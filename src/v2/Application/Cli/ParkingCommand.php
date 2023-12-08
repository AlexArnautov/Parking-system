<?php

declare(strict_types=1);

namespace App\v2\Application\Cli;

use App\v2\Domain\Entity\GarageFloor;
use App\v2\Domain\Entity\Vehicle\Car;
use App\v2\Domain\Entity\Vehicle\Van;
use App\v2\Infrastructure\ParkingStrategy\ParkingStrategyFactory;
use Symfony\Component\Console;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:parking')]
class ParkingCommand extends Console\Command\Command
{
    private const SUCCESS_MSG = 'Welcome, please go in';
    private const REJECT_MSG = 'Sorry, no spaces left';
    private const TITLE_MSG = 'Emulate parking garage.';

    public function __construct(
        private readonly ParkingStrategyFactory $parkingStrategyFactory,
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title(self::TITLE_MSG);

        $vehicle = new Van();

        $floors = [
            new GarageFloor(0, 10, 9.5, false),
            new GarageFloor(1, 15, 14.5, false),
            new GarageFloor(2, 10, 8.5, true),
        ];

        $parkingStrategy = $this->parkingStrategyFactory->create($vehicle);

        $isParked = false;
        foreach ($floors as $floor) {
            if ($parkingStrategy->isAvailable($floor, $vehicle)) {
                $parkingStrategy->park($floor, $vehicle);
                $isParked = true;
                break;
            }
        }

        if ($isParked) {
            $io->success(self::SUCCESS_MSG);
        } else {
            $io->warning(self::REJECT_MSG);
        }

        return self::SUCCESS;
    }
}
