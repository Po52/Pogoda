<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;


final class ForecastController extends AbstractController
{
    // #[Route('/forecast/{id}', name: 'app_forecast', requirements: ['id' => '\d+'])]
    // public function city(Location $location, ForecastRepository $repository): Response
    // {
    //     $measurements = $repository->findByLocation($location);

    //     return $this->render('forecast/city.html.twig', [
    //         'location' => $location,
    //         'measurements' => $measurements,
    //     ]);
    // }
    #[Route('forecast/{city}', name: 'app_forecast_city')]
    public function city(
        #[MapEntity(mapping: ['city' => 'city'])] Location $location,
        ForecastRepository $repository
    ): Response{
        $measurements = $repository->findByLocation($location);

        return $this->render('forecast/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
