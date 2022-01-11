<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class EspaceController extends AbstractController
{
    /**
     * @Route("/espace-membre", name="espace")
     */
    public function index(): Response
    {
        return $this->render('espace/index.html.twig');
    }

    /**
     * @Route("/espace-membre/detail-commande-{id}", name="espace_detail")
     */
    public function detail(Commande $commande): Response
    {
        $commandesUtilisateurConnecte = $this->getUser()->getCommandes();
        if( $this->isGranted("ROLE_ADMIN") || $commandesUtilisateurConnecte->contains($commande) ){
            return $this->render('espace/detail_commande.html.twig', [ "commande" => $commande ]);
        } else {
            $this->addFlash("danger", "Vous ne pouvez pas afficher cette commande");
            return $this->redirectToRoute("espace");
        }
    }
}
