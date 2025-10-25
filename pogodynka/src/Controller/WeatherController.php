<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather', defaults: ['country' => null])]
    public function city(
        string $city,
        ?string $country,
        LocationRepository $locationRepository,
        MeasurementRepository $measurementRepository
    ): Response {
        $city = str_replace('-', ' ', trim(urldecode($city)));
        $cityLower = mb_strtolower($city);

        $qb = $locationRepository->createQueryBuilder('l')
            ->where('LOWER(l.city) = :city')
            ->setParameter('city', $cityLower)
            ->setMaxResults(1);

        if ($country !== null) {
            $country = strtoupper(trim(urldecode($country)));
            $qb->andWhere('LOWER(l.country) = :country')
                ->setParameter('country', mb_strtolower($country));
        }

        $location = $qb->getQuery()->getOneOrNullResult();

        if (!$location) {
            throw $this->createNotFoundException(sprintf(
                'Location "%s"%s not found.',
                $city,
                $country ? " ($country)" : ''
            ));
        }

        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
