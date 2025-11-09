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

#[AsCommand(
    name: 'location:CountryCity',
    description: 'Add a short description for your command',
)]
class LocationCountryCityCommand extends Command
{
    public function __construct(
        private ForecastUtil $forecastUtil,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country code')
            ->addArgument('city', InputArgument::REQUIRED, 'City name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $forecasts = $this->forecastUtil->getForecastForCountryandCity($country, $city);

        $io->writeln(sprintf('Location: %s',$country, $city));
        $io->table(['Date', 'Temperature', 'UV Index', 'Wind'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperatureInCelsius(),
            $m->getUvIndex(),
            $m->getWind(),
        ], $forecasts));


        return Command::SUCCESS;
    }
}
