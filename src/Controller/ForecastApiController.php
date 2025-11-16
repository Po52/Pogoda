<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\ForecastUtil;
use App\Entity\Forecast;
use Symfony\Component\HttpFoundation\Response;





final class ForecastApiController extends AbstractController
{
    public function __construct(
        private ForecastUtil $forecastUtil,
    ){}

    #[Route('/forecast/api/v1/forecast', name: 'app_forecast_api')]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ):Response
    {
        $forecasts = $this->forecastUtil->getForecastForCountryandCity($country, $city);

        if($format == 'json' && $twig){
            return $this->render('forecast_api/index.json.twig',[
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecasts,
            ]);
        }

        if($format == 'json'){
            return $this->json([
                'city' => $city,
                'country' => $country,
                'forecasts' => array_map(fn(Forecast $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'TemperatureInCelsius' => $m->getTemperatureInCelsius(),
                    'Fahrenheit' => $m->getFahrenheit(),
                    'UvIndex' => $m->getUvIndex(),
                    'Wind' => $m->getWind(),
                ], $forecasts),
            ]);
        }
        if ($format == 'csv' && $twig){
            return $this->render('forecast_api/index.csv.twig',[
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecasts,
            ],
            new Response(
                '',
                200,
                ['Content-Type' => 'text/plain']
            )
            );
        }

        elseif($format =='csv'){
            $arr = [];
            foreach ($forecasts as $m){
                $arr[] = implode(',',[
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTemperatureInCelsius(),
                    $m->getFahrenheit(),
                    $m->getUvIndex(),
                    $m->getWind(),
                ]);                
            }
            $csv = implode("\n", $arr);
            return new Response(
                $csv,
                200,
                ['Content-Type' => 'text/csv']
            );
        }            
    }
}
