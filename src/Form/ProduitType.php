<?php

namespace App\Form;

use App\Entity\Produit, App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                "choice_label" => "libelle",
                "placeholder" => ""
            ])
            ->add('titre')
            ->add('description')
            ->add('couleur')
            ->add('public')
            ->add('photo')
            ->add('prix')
            ->add('stock')
            ->add('taille')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}