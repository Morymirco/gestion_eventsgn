<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Registration;
use App\Repository\EventRepository;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/events')]
class PublicEventController extends AbstractController
{
    #[Route('/', name: 'app_public_events')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findActiveEvents();

        return $this->render('public_event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/{id}', name: 'app_public_event_show')]
    public function show(Event $event, RegistrationRepository $registrationRepository): Response
    {
        $userRegistration = null;
        if ($this->getUser()) {
            $userRegistration = $registrationRepository->findByUserAndEvent($this->getUser(), $event);
        }

        return $this->render('public_event/show.html.twig', [
            'event' => $event,
            'user_registration' => $userRegistration,
        ]);
    }

    #[Route('/{id}/register', name: 'app_public_event_register', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function register(Request $request, Event $event, RegistrationRepository $registrationRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('register'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
        }

        // Check if user is already registered
        $existingRegistration = $registrationRepository->findByUserAndEvent($this->getUser(), $event);
        if ($existingRegistration) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
        }

        // Check if event is full
        if ($event->isFull()) {
            $this->addFlash('error', 'Cet événement est complet.');
            return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
        }

        // Create registration
        $registration = new Registration();
        $registration->setUser($this->getUser());
        $registration->setEvent($event);

        $entityManager->persist($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Votre inscription a été enregistrée. Elle sera confirmée par un administrateur.');

        return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
    }

    #[Route('/{id}/unregister', name: 'app_public_event_unregister', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function unregister(Request $request, Event $event, RegistrationRepository $registrationRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('unregister'.$event->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
        }

        $registration = $registrationRepository->findByUserAndEvent($this->getUser(), $event);
        if (!$registration) {
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cet événement.');
            return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Votre inscription a été annulée.');

        return $this->redirectToRoute('app_public_event_show', ['id' => $event->getId()]);
    }
}