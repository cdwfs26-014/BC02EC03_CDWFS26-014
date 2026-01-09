<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Evenement;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        /*
        ============================
        UTILISATEURS
        ============================
        */

        // --- Utilisateurs standards ---
        $user1 = new Utilisateur();
        $user1->setEmail('user1@test.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->hasher->hashPassword($user1, 'user1pass'));
        $manager->persist($user1);

        $user2 = new Utilisateur();
        $user2->setEmail('user2@test.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->hasher->hashPassword($user2, 'user2pass'));
        $manager->persist($user2);

        $user3 = new Utilisateur();
        $user3->setEmail('user3@test.com');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPassword($this->hasher->hashPassword($user3, 'user3pass'));
        $manager->persist($user3);

        // --- Responsables ---
        $resp1 = new Utilisateur();
        $resp1->setEmail('responsable1@test.com');
        $resp1->setRoles(['ROLE_RESPONSABLE']);
        $resp1->setPassword($this->hasher->hashPassword($resp1, 'resp1pass'));
        $manager->persist($resp1);

        $resp2 = new Utilisateur();
        $resp2->setEmail('responsable2@test.com');
        $resp2->setRoles(['ROLE_RESPONSABLE']);
        $resp2->setPassword($this->hasher->hashPassword($resp2, 'resp2pass'));
        $manager->persist($resp2);

        // --- Administrateur ---
        $admin = new Utilisateur();
        $admin->setEmail('admin@test.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'adminpass'));
        $manager->persist($admin);

        /*
        ============================
        EVENEMENTS
        ============================
        */

        $e1 = (new Evenement())->setTitre('Concert Rock');
        $e2 = (new Evenement())->setTitre('Festival de cinéma');
        $e3 = (new Evenement())->setTitre('Salon du jeu vidéo');
        $e4 = (new Evenement())->setTitre('Conférence tech');
        $e5 = (new Evenement())->setTitre('Marathon');
        $e6 = (new Evenement())->setTitre('Exposition d’art');
        $e7 = (new Evenement())->setTitre('Spectacle de magie');
        $e8 = (new Evenement())->setTitre('Soirée étudiante');
        $e9 = (new Evenement())->setTitre('Tournoi e-sport');
        $e10 = (new Evenement())->setTitre('Marché de Noël');

        foreach ([$e1,$e2,$e3,$e4,$e5,$e6,$e7,$e8,$e9,$e10] as $event) {
            $manager->persist($event);
        }

        /*
        ============================
        AVIS
        ============================
        */

        $avisData = [
            [$user1, $e1, 5, 'Super concert', true],
            [$user2, $e1, 4, 'Très sympa', false],
            [$user3, $e2, 3, 'Pas mal', true],
            [$user1, $e2, 2, 'Décevant', false],
            [$user2, $e3, 5, 'Incroyable', true],
            [$user3, $e3, 4, 'Très bien', true],
            [$user1, $e4, 3, 'Correct', false],
            [$user2, $e4, 4, 'Intéressant', true],
            [$user3, $e5, 5, 'Génial', true],
            [$user1, $e6, 4, 'Très joli', false],
            [$user2, $e7, 5, 'Magique', true],
            [$user3, $e8, 3, 'Moyen', false],
            [$user1, $e9, 5, 'Excellent', true],
            [$user2, $e10, 4, 'Sympa', true],
            [$user3, $e10, 2, 'Bof', false],
        ];

        foreach ($avisData as [$user, $event, $note, $commentaire, $accepter]) {
            $a = (new Avis())
                ->setAU($user)
                ->setEvenement($event)
                ->setNote($note)
                ->setCommentaire($commentaire)
                ->setAccepter($accepter)
                ->setCreerLe(new \DateTime())
                ->setModifierLe(new \DateTime());

            $manager->persist($a);
        }

        $manager->flush();
    }
}
