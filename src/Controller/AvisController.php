<?php

namespace App\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/avis')]
class AvisController extends AbstractController
{

    #[Route('/moderate/{id}', name: 'app_avis_moderate')]
    #[IsGranted('ROLE_USER')]
    public function moderate(Avis $avis, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        // Vérifie si admin ou responsable
        if (!in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_RESPONSABLE', $roles)) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modérer cet avis.');
        }

        $accepter = $request->query->get('accepter') === '1';

        $avis->setAccepter($accepter); // true = validé, false = non validé
        $em->flush();

        $this->addFlash('success', $accepter ? 'Avis accepté.' : 'Avis refusé, il n’est plus visible publiquement.');
        return $this->redirectToRoute('app_evenement_show', ['id' => $avis->getEvenement()->getId()]);
    }
}
