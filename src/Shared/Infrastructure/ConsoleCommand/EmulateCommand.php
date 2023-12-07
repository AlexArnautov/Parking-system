<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ConsoleCommand;

use App\Floor\Domain\Entity\Floor;
use App\Floor\Domain\Exception\WrongVehicleTypeException;
use App\ParkingGarage\Domain\Entity\ParkingGarage;
use App\ParkingGarage\Domain\Entity\ParkingGarageInterface;
use App\ParkingGarage\Domain\Service\ParkingManagerServiceInterface;
use App\Vehicle\Domain\Entity\Car;
use App\Vehicle\Domain\Entity\Motorcycle;
use App\Vehicle\Domain\Entity\Van;
use App\Vehicle\Domain\Factory\RandomVehicleFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EmulateCommand extends Console\Command\Command
{
    private const SUCCESS_MSG = 'Welcome, please go in';
    private const REJECT_MSG = 'Sorry, no spaces left';
    private const TITLE_MSG = 'Emulate parking garage.';

    protected static string $defaultName = 'emulate';

    public function __construct
    (
        private readonly ParkingManagerServiceInterface $vehicleManager,
        private readonly RandomVehicleFactory $vehicleFactory,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Emulate parking garage');
    }

    /**
     * @throws WrongVehicleTypeException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title(self::TITLE_MSG);
        $this->logger->info(self::TITLE_MSG);

        $groundFloor = new Floor(
            capacity: 6,
            allowedVehicleTypes: [Car::class, Van::class, Motorcycle::class]
        );

        $secondFloor = new Floor(
            capacity: 5,
            allowedVehicleTypes: [Car::class, Motorcycle::class]
        );

        $thirdFloor = new Floor(
            capacity: 5,
            allowedVehicleTypes: [Car::class, Motorcycle::class]
        );

        $parkingGarage = new ParkingGarage([$groundFloor, $secondFloor, $thirdFloor]);

        $io->info('Initial capacity');
        $this->printGarageStats($parkingGarage, $io);

        for ($i = 1; $i <= 30; $i++) {
            $io->newLine();
            $vehicle = $this->vehicleFactory->createRandomVehicle();
            $io->newLine();
            $io->writeln('Trying parking a ' . (new \ReflectionClass($vehicle))->getShortName());
            $result = $this->vehicleManager->parkVehicle($parkingGarage, $vehicle);
            $this->printParkingResult($result, $io);
            $this->printGarageStats($parkingGarage, $io);
            sleep(1);
        }

        return self::SUCCESS;
    }

    private function printGarageStats(ParkingGarageInterface $parkingGarage, SymfonyStyle $io): void
    {
        foreach ($parkingGarage->getFloors() as $number => $floor) {
            $msg = 'Floor ' . $number . ' space left: ' . $floor->getLeftCapacity();
            $io->writeln($msg);
            $this->logger->info($msg);
        }
    }

    private function printParkingResult(bool $result, SymfonyStyle $io): void
    {
        if ($result === true) {
            $io->success(self::SUCCESS_MSG);
            $this->logger->info(self::SUCCESS_MSG);
            return;
        }

        $io->warning(self::REJECT_MSG);
        $this->logger->info(self::REJECT_MSG);
    }
}