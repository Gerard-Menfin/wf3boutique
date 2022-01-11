<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <= 5 ; $i++) { 
            $categorie = new Categorie;
            $categorie->setLibelle("categorie$i");
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
