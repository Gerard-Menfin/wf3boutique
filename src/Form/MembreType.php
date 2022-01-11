<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $membre = $options["data"];
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Gérant" => "ROLE_ADMIN",
                    "Vendeur" => "ROLE_VENDEUR",
                    "Membre" => "ROLE_USER"
                ],
                "multiple" => true,
                "expanded" => true,
                "label" => "Droit d'accès"
            ])
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "label" => "Mot de passe",
                "required" => $membre->getId() ? false : true,
            ])
            ->add('civilite', ChoiceType::class, [
                "choices" => [
                    "Mme" => "f",
                    "M."  => "h",
                    "autre" => "a"
                ],
                "multiple" => false,
                "expanded" => true
            ])
            ->add('prenom')
            ->add('nom')
            ->add('adresse')
            ->add('code_postal', TextType::class, [
                "label" => "Code postal",
                "constraints" => [
                    new Regex([
                        // "pattern" => "/^\d{5}$/"
                        "pattern" => "/^((0[1-9])|([1-8][0-9])|(9[0-8]))[0-9]{3}$/",
                        "message" => "Le code postal n'est pas valide"
                    ])
                    ],
                    "help" => "Le code postal doit comporter 5 chiffres"
            ])
            ->add('ville')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
