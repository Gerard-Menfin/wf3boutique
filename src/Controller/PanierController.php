<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Detail;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session): Response
    {
        return $this->render('panier/index.html.twig', [
            'panier' => $session->get("panier", [])
        ]);
    }

    /**
     * @Route("/ajouter-au-panier/{id}", name="panier_ajouter")
     * 
     * Classe Session : méthodes
     *      get : pour récupérer un indice de $_SESSION
     *      set : pour définir   un indice de $_SESSION
     *   remove : pour supprimer un indice de $_SESSION
     * 
     * Objet de la classe Request : contient toutes les valeurs des superglobales de PHP ($_GET, $_POST, ...)
     * $_GET
     */
    public function ajouter(Request $rq, SessionInterface $session, Produit $produit): Response
    {
        $qte = $rq->query->get("qte", 1);
        $panier = $session->get("panier", []);
        $produitExiste = false;
        foreach ($panier as $indice => $ligne) {
            if( $produit->getId() == $ligne["produit"]->getId() ){
                $panier[$indice]["quantite"] += $qte;
                $produitExiste = true;
            } 
        }
        if( !$produitExiste ){
            $panier[] = [ "produit" => $produit, "quantite" => $qte ];
        }

        $session->set("panier", $panier);
        return $this->redirectToRoute("home");
    }
    
    /**
     * @Route("/ajax-ajouter-au-panier/{id}", name="panier_ajouter_ajax")
     */
    public function ajax_ajouter(Request $rq, SessionInterface $session, Produit $produit): Response
    {
        $qte = $rq->query->get("qte", 1);
        $panier = $session->get("panier", []);
        $produitExiste = false;
        foreach ($panier as $indice => $ligne) {
            if( $produit->getId() == $ligne["produit"]->getId() ){
                $panier[$indice]["quantite"] += $qte;
                $produitExiste = true;
            } 
        }
        if( !$produitExiste ){
            $panier[] = [ "produit" => $produit, "quantite" => $qte ];
        }

        $session->set("panier", $panier);
        return $this->json(true);
    }
    


    /**
     * @Route("/vider-le-panier", name="panier_vider")
     * 
    */
    public function vider(SessionInterface $session)
    {
        $session->remove("panier");
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/valider-le-panier", name="panier_valider")
     * @IsGranted("ROLE_USER")
     * 
    */
    #[IsGranted("ROLE_USER")]
    public function valider(SessionInterface $session, EntityManagerInterface $em, ProduitRepository $pr)
    {
        $panier = $session->get("panier");
        if( $panier ){
            $cmd = new Commande;
            $montant = 0;
            $cmd->setEtat("en attente");
            $cmd->setDateEnregistrement( new \DateTime() );
            $cmd->setMembre( $this->getUser() );
            foreach ($panier as $ligne) {
                $detail = new Detail;
                $detail->setCommande( $cmd );
                $detail->setQuantite( $ligne["quantite"] );
                /* le produit du panier est récupéré dans la session. Il a donc été sérialisé (= transformé en string).
                    Pour l'EntityManager, cet objet de la classe Entity\Produit est donc un nouveau produit, pas un produit
                    récupéré dans la base de données. Quand flush() sera exécuté, un nouveau produit sera ajouté dans la bdd, si
                    on utiliser le produit de la session. C'est pourquoi on récupère à nouveau le produit avec le ProduitRepository
                    avant de l'affecter à l'objet Detail
                */
                $produit = $pr->find( $ligne["produit"]->getId() );
                $detail->setProduit( $produit );
                $detail->setPrix( $produit->getPrix() );

                $montant += $produit->getPrix() * $ligne["quantite"];
                $produit->setStock( $produit->getStock() - $ligne["quantite"]);
                $em->persist($detail);
            }
            $cmd->setMontant( $montant );
            $em->persist($cmd);
            $em->flush();
            $session->remove("panier");
            return $this->redirectToRoute("espace");
        }
    }

}
