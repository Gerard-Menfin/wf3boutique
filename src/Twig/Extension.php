<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Entity\Membre;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use DateTime;

class Extension extends AbstractExtension {
    public function autorisations(Membre $membre)
    {
        $autorisations = "";
        foreach( $membre->getRoles() as $role){
            $autorisations .= $autorisations ? ", " : "";
            switch ($role) {
                case 'ROLE_ADMIN':
                    $autorisations .= "Gérant";
                    break;
                
                case 'ROLE_VENDEUR':
                    $autorisations .= "Vendeur";
                    break;
                
                default:
                    $autorisations .= "Membre";
                    break;
            }
        }
        return $autorisations;
    }

    public function salut(Membre $membre, $auj = null)
    {
        $salutations = "Bonjour ";
        if( !empty($membre->getPrenom()) || !empty($membre->getNom()) ){
            $salutations .= $membre->getPrenom() . " " . $membre->getNom();
        } else {
            $salutations .= $membre->getEmail();
        }
        $salutations .= ", nous sommes le ";
        $auj = $auj ?? (new DateTime())->format("d/m/Y");
        $salutations .= $auj;
        return $salutations;
    }

    /**
     * Les filtres que l'on veut ajouter doivent être renvoyés dans un array par la fonction getFilters
     * Chaque valeur de cet array est un objet de la classe TwigFilter
     * Les arguments du constructeur de TwigFilter :
     *      1er : le nom du filtre à utiliser dans les fichiers Twig
     *      2eme : la fonction qui est déclaré dans cette classe 
     *                  [ $this, nom_de_la_fonction_dans_la_classe ]
     */
    public function getFilters()
    {
        return [
            new TwigFilter("autorisations", [$this, "autorisations"]),
            new TwigFilter("salutations", [$this, "salut"]),
            
        ];
    }

    public function getFunctions(){
        return [
            new TwigFunction("autorisations", [$this, "autorisations"])
        ];
    }

}