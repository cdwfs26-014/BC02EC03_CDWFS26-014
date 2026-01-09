<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Evenement;
use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $em)
    {
        $users = $em->getRepository(Utilisateur::class)->findAll();
        $evenements = $em->getRepository(Evenement::class)->findAll();
        $avis = $em->getRepository(Avis::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'evenements' => $evenements,
            'avis' => $avis,
        ]);
    }
}
