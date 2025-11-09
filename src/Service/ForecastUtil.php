<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Forecast;
use App\Repository\ForecastRepository;
use App\Repository\LocationRepository;

class ForecastUtil
{
    public function __construct(
        private ForecastRepository $forecastRepository,
        private LocationRepository $locationRepository,
    ) {}

    /**
     * @return Forecast[]
     */
    public function getForecastForLocation(Location $location): array
    {
        return $this->forecastRepository->findBy(['location' => $location]);
    }

    /**
     * @return Forecast[]
     */
    public function getForecastForCountryAndCity(string $country, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        if (!$location) {
            return [];
        }

        return $this->getForecastForLocation($location);
    }
}
