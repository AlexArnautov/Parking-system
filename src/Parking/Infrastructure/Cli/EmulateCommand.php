<?php

declare(strict_types=1);

namespace App\Parking\Infrastructure\Cli;

use App\Parking\Application\ParkingStrategy\ParkingStrategyFactory;
use App\Parking\Application\ParkingStrategy\ParkingStrategyInterface;
use App\Parking\Domain\Entity\Floor;
use App\Parking\Domain\Entity\ParkingGarage;
use App\Parking\Domain\Enum\VehicleType;
use App\Parking\Infrastructure\Factory\RandomVehicleFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class EmulateCommand extends Console\Command\Command
{
    private const SUCCESS_MSG = 'Welcome, please go in';
    private const REJECT_MSG = 'Sorry, no spaces left';
    private const TITLE_MSG = 'Emulate parking garage.';
    const LOG_FILE = '/app.log';

    private SymfonyStyle $io;

    protected static string $defaultName = 'emulate';

    public function __construct
    (
        private readonly RandomVehicleFactory $vehicleFactory,
        private readonly ParkingStrategyFactory $parkingStrategyFactory,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Emulate parking garage');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title(self::TITLE_MSG);
        $this->logger->info(self::TITLE_MSG);

        $helper = $this->getHelper('question');
        $question = new Question('Please enter number of car spots in the ground floor <info>(default: 6)</info>', 6);
        $groundFloorCapacity = (int)$helper->ask($input, $output, $question);

        $question = new Question('Please enter number of car spots in the second floor <info>(default: 5)</info>', 5);
        $secondFloorCapacity = (int)$helper->ask($input, $output, $question);

        $question = new Question('Please enter number of car spots in the third floor <info>(default: 5)</info>', 5);
        $thirdFloorCapacity = (int)$helper->ask($input, $output, $question);

        $question = new Question('Please enter number of random vehicles amount <info>(default: 30)</info>', 30);
        $vehicleAmount = (int)$helper->ask($input, $output, $question);

        $groundFloor = new Floor(
            capacity: $groundFloorCapacity,
            allowedVehicleTypes: [VehicleType::Moto, VehicleType::Car, VehicleType::Van]
        );

        $secondFloor = new Floor(
            capacity: $secondFloorCapacity,
            allowedVehicleTypes: [VehicleType::Car, VehicleType::Moto]
        );

        $thirdFloor = new Floor(
            capacity: $thirdFloorCapacity,
            allowedVehicleTypes: [VehicleType::Car, VehicleType::Moto]
        );

        $parkingGarage = new ParkingGarage([$groundFloor, $secondFloor, $thirdFloor]);

        $this->io->info('Initial capacity');
        $this->printGarageStats($parkingGarage);

        for ($i = 1; $i <= $vehicleAmount; $i++) {
            $vehicle = $this->vehicleFactory->createRandomVehicle();
            $this->io->writeln('Trying parking a <comment>' . $vehicle->type->name.'</comment>');

            /** @var ParkingStrategyInterface $parkingStrategy */
            $parkingStrategy = $this->parkingStrategyFactory->getStrategy($vehicle);
            $result = $parkingStrategy->park($parkingGarage, $vehicle);
            $this->printParkingResult($result);
            $this->printGarageStats($parkingGarage);
            $this->io->newLine();
            $this->io->newLine();
            sleep(1);
        }

        $this->io->info('You can check the file for logs '.getcwd(). self::LOG_FILE);

        return self::SUCCESS;
    }

    private function printGarageStats(ParkingGarage $parkingGarage): void
    {
        foreach ($parkingGarage->getFloors() as $number => $floor) {
            $msg = 'Floor ' . $number . ' space left: ' . $floor->getLeftCapacity();
            $this->io->writeln($msg);
            $this->logger->info($msg);
        }
    }

    private function printParkingResult(bool $result): void
    {
        if ($result === true) {
            $this->io->success(self::SUCCESS_MSG);
            $this->logger->info(self::SUCCESS_MSG);
            return;
        }

        $this->io->warning(self::REJECT_MSG);
        $this->logger->info(self::REJECT_MSG);
    }
}