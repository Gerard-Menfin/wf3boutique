<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $rq, ProduitRepository $pr): Response
    {
        $mot = $rq->query->get("mot");
        $produits = $pr->findProduitRecherche($mot);
        return $this->render('recherche/titre.html.twig', [
            'produits' => $produits,
            'mot' => $mot
        ]);
    }

    /**
     * @Route("/recherche-par-prix", name="recherche_prix")
     */
    public function recherchePrix(Request $rq, ProduitRepository $pr): Response
    {
        $mot = $rq->query->get("mot");
        // findProduitParPrix doit renvoyé les produits dont les prix sont inférieurs à celui tapé dans la barre de recherche
        // trier par prix et ordre décroissant
        $produits = $pr->findProduitPrixRecherche($mot);
        return $this->render('recherche/prix.html.twig', [
            'produits' => $produits,
            'mot' => $mot
        ]);
    }
}
