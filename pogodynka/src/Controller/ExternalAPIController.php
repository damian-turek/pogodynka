<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExternalAPIController extends AbstractController
{
    #[Route('/external/a/p/i', name: 'app_external_a_p_i')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ExternalAPIType::class);
        $weatherData = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $latitude  = $data['latitude'];
            $longitude = $data['longitude'];

            $apiUrl = "https://api.open-meteo.com/v1/forecast?latitude={$latitude}&longitude={$longitude}&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=auto";

            $response = @file_get_contents($apiUrl);

            if ($response !== false) {
                $weatherData = json_decode($response, true);
            } else {
                $this->addFlash('error', 'Nie można pobrać danych pogodowych');
            }
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form,
            'weatherData' => $weatherData,
        ]);
    }
}
