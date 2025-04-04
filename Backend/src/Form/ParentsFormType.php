<?php

namespace App\Form;

use App\Entity\Parents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParentsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emailParent', EmailType::class, [
                'label' => 'Email',
                'required' => false,

            ])
            ->add('nomParent', TextType::class, [
                'label' => 'Nom',
                'required' => false,

            ])
            ->add('prenomParent', TextType::class, [
                'label' => 'Prénom',
                'required' => false,

            ])
            ->add('telParent', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,

            ])
            ->add('adresseParent', TextType::class, [
                'label' => 'Adresse',
                'required' => false, // Si l'adresse est optionnelle
            ]);

        // Ajouter le champ password uniquement pour un nouvel utilisateur
        if ($options['is_add']) {
            $builder->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => true, // Le champ est lié à l'entité
                'always_empty' => false, 

            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parents::class,
            'is_add' => false, // Définir la valeur par défaut
        ]);
    }
}
