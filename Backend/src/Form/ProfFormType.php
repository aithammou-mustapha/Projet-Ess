<?php

namespace App\Form;

use App\Entity\Prof;
use App\Entity\Centre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProf', TextType::class, [
                'label' => 'Nom',
                'required' => false,

            ])
            ->add('prenomProf', TextType::class, [
                'label' => 'Prénom',
                'required' => false,

            ])
            ->add('emailProf', EmailType::class, [
                'label' => 'Email',
                'required' => false,

            ])
            ->add('telProf', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,

            ])
            ->add('disponibilitesProf', TextType::class, [
                'label' => 'Disponibilités',
                'required' => false, 
            ])

            ->add('dateEnregistrementProf', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'input' => 'datetime_immutable',
                'data' => new \DateTimeImmutable(), // Toujours aujourd'hui
                'attr' => ['readonly' => true] // L'utilisateur ne peut pas modifier
            ])
            
            ->add('centres', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'nomCentre',
                'multiple' => false, // ❌ Rend le champ en sélection unique
                'label' => 'Centre associé',
                'placeholder' => 'Sélectionnez un centre', // Optionnel, ajoute un choix vide
                'mapped' => false, // ✅ Important pour éviter l’erreur Doctrine
                'required' => false,

            ]);
            

        if ($options['is_add']) {
            $builder->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => true,
                'always_empty' => false, 

            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prof::class,
            'is_add' => false, 
        ]);
    }
}
