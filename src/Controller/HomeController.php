<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EventRepository $eventRepository): Response
    {
        $upcomingEvents = $eventRepository->findUpcomingEvents();
        
        return $this->render('home/index.html.twig', [
            'upcoming_events' => array_slice($upcomingEvents, 0, 3), // Show only 3 upcoming events
        ]);
    }
}