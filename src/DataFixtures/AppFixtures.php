<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create categories
        $categories = [
            ['name' => 'Conférence', 'description' => 'Événements de type conférence et séminaire'],
            ['name' => 'Formation', 'description' => 'Sessions de formation et ateliers'],
            ['name' => 'Networking', 'description' => 'Événements de réseautage professionnel'],
            ['name' => 'Culturel', 'description' => 'Événements culturels et artistiques'],
        ];

        $categoryObjects = [];
        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setDescription($categoryData['description']);
            $manager->persist($category);
            $categoryObjects[] = $category;
        }

        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('System');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Create regular users
        $users = [
            ['email' => 'user1@example.com', 'firstName' => 'Jean', 'lastName' => 'Dupont'],
            ['email' => 'user2@example.com', 'firstName' => 'Marie', 'lastName' => 'Martin'],
            ['email' => 'user3@example.com', 'firstName' => 'Pierre', 'lastName' => 'Durand'],
        ];

        $userObjects = [];
        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $manager->persist($user);
            $userObjects[] = $user;
        }

        // Create events
        $events = [
            [
                'title' => 'Conférence Symfony 2025',
                'description' => 'Découvrez les nouveautés de Symfony 7 et les meilleures pratiques de développement.',
                'startDate' => new \DateTime('+1 week'),
                'endDate' => new \DateTime('+1 week +8 hours'),
                'location' => 'Centre de conférences, Paris',
                'maxParticipants' => 100,
                'category' => $categoryObjects[0],
            ],
            [
                'title' => 'Formation PHP Avancé',
                'description' => 'Formation intensive sur les concepts avancés de PHP et les design patterns.',
                'startDate' => new \DateTime('+2 weeks'),
                'endDate' => new \DateTime('+2 weeks +2 days'),
                'location' => 'Salle de formation, Lyon',
                'maxParticipants' => 25,
                'category' => $categoryObjects[1],
            ],
            [
                'title' => 'Meetup Développeurs Web',
                'description' => 'Rencontrez d\'autres développeurs et partagez vos expériences.',
                'startDate' => new \DateTime('+3 days'),
                'endDate' => new \DateTime('+3 days +4 hours'),
                'location' => 'Espace coworking, Marseille',
                'maxParticipants' => 50,
                'category' => $categoryObjects[2],
            ],
            [
                'title' => 'Exposition Art Numérique',
                'description' => 'Découvrez les dernières créations d\'art numérique et interactif.',
                'startDate' => new \DateTime('+1 month'),
                'endDate' => new \DateTime('+1 month +1 week'),
                'location' => 'Galerie d\'art moderne, Toulouse',
                'maxParticipants' => 200,
                'category' => $categoryObjects[3],
            ],
        ];

        foreach ($events as $eventData) {
            $event = new Event();
            $event->setTitle($eventData['title']);
            $event->setDescription($eventData['description']);
            $event->setStartDate($eventData['startDate']);
            $event->setEndDate($eventData['endDate']);
            $event->setLocation($eventData['location']);
            $event->setMaxParticipants($eventData['maxParticipants']);
            $event->setCategory($eventData['category']);
            $event->setActive(true);
            $manager->persist($event);
        }

        $manager->flush();
    }
}