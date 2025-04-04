<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Gerant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GerantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emailGerant', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ])
            ->add('nomGerant', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('prenomGerant', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
            ])
            ->add('telGerant', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => false, 
                'required' => false,
                'mapped' => false, 
                'placeholder' => 'Sélectionnez un rôle', 
            ])
            ->add('centres', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'nomCentre',
                'expanded' => false, // <select>
                'required' => false,
                'mapped' => false, 
                'placeholder' => 'Sélectionnez un centre',
            ])
            
            
            ->add('avatarGerant', FileType::class, [
                'label' => 'Avatar',
                'required' => false,
                'mapped' => false, // On gère l'upload nous-mêmes
            ]);

        if ($options['is_add']) {
            $builder->add('password', PasswordType::class, [
                'required' => false,
                'always_empty' => false, 
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gerant::class,
            'is_add' => false,
        ]);
    }
}
