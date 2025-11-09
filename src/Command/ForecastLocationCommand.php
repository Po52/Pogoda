<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\ForecastUtil;
use App\Repository\LocationRepository;

#[AsCommand(
    name: 'forecast:location',
    description: 'Add a short description for your command',
)]
class ForecastLocationCommand extends Command
{
    public function __construct(
        private LocationRepository $locationRepository,
        private ForecastUtil $forecastUtil,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Id location');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('id');
        $location = $this->locationRepository->find($locationId);

        $forecasts = $this->forecastUtil->getForecastForLocation($location);

        $io->writeln(sprintf('Location: %s', $location->getCity()));
        $io->table(['Date', 'Temperature', 'UV Index', 'Wind'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperatureInCelsius(),
            $m->getUvIndex(),
            $m->getWind(),
        ], $forecasts));


        return Command::SUCCESS;
    }
}
