<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Avis;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/evenements')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $order = $request->query->get('order', 'asc');
        $filtreMesAvis = $request->query->get('mes_avis') === '1';
        $filtreSansAvis = $request->query->get('sans_avis') === '1';
        $user = $this->getUser();

        $repo = $em->getRepository(Evenement::class);
        $evenements = $repo->findBy([], ['titre' => $order === 'desc' ? 'DESC' : 'ASC']);

        if ($user && ($filtreMesAvis || $filtreSansAvis)) {
            $evenements = array_filter($evenements, function($event) use ($user, $filtreMesAvis, $filtreSansAvis) {
                $monAvis = null;
                foreach ($event->getAE() as $a) {
                    if ($a->getAU() && $a->getAU()->getId() === $user->getId()) {
                        $monAvis = $a;
                        break;
                    }
                }
                if ($filtreMesAvis && $monAvis) return true;
                if ($filtreSansAvis && !$monAvis) return true;
                return !$filtreMesAvis && !$filtreSansAvis;
            });
        }

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
            'order' => $order,
            'mesAvis' => $filtreMesAvis,
            'sansAvis' => $filtreSansAvis
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show')]
    public function show(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $avisRepository = $em->getRepository(Avis::class);

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_RESPONSABLE', $user->getRoles()))) {
            $avisVisibles = $avisRepository->findBy(['evenement' => $evenement]);
        } else {
            $avisVisibles = $avisRepository->findBy(['evenement' => $evenement, 'accepter' => true]);
        }

        $monAvis = $user ? $avisRepository->findOneBy(['evenement' => $evenement, 'a_U' => $user]) : null;

        $avisForm = null;
        if ($user) {
            $avisObj = $monAvis ?? new Avis();
            $form = $this->createForm(AvisType::class, $avisObj);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $avisObj->setAU($user);
                $avisObj->setEvenement($evenement);
                if (!$monAvis) $avisObj->setAccepter(false);
                $avisObj->setCreerLe($avisObj->getCreerLe() ?? new \DateTime());
                $avisObj->setModifierLe(new \DateTime());

                $em->persist($avisObj);
                $em->flush();

                $this->addFlash('success', 'Votre avis a été enregistré. Il sera visible après validation par un responsable ou un administrateur.');
                return $this->redirectToRoute('app_evenement_show', ['id' => $evenement->getId()]);
            }

            $avisForm = $form->createView();
        }

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'avis' => $avisVisibles,
            'monAvis' => $monAvis,
            'avisForm' => $avisForm,
            'user' => $user
        ]);
    }

    #[Route('/avis/toggle/{id}', name: 'app_avis_toggle')]
    #[IsGranted('ROLE_USER')]
    public function toggleAvis(Avis $avis, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (!in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_RESPONSABLE', $roles)) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modérer cet avis.');
        }

        $avis->setAccepter(!$avis->isAccepter());
        $em->flush();

        $this->addFlash('success', 'Avis modéré avec succès.');
        return $this->redirectToRoute('app_evenement_show', ['id' => $avis->getEvenement()->getId()]);
    }
}
