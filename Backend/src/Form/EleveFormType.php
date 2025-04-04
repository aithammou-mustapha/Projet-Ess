<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Eleve;
use App\Entity\Groupe;
use App\Entity\Parents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ✅ Champs de l'élève
            ->add('nomEleve', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('prenomEleve', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
            ])
            ->add('niveau', TextType::class, [
                'label' => 'Niveau',
                'required' => false,
            ])
            ->add('etablissementScolaire', TextType::class, [ // ✅ correction ici
                'label' => 'Établissement scolaire',
                'required' => false,
            ])
            ->add('telEleve', TelType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
            ])

            // ✅ Centre
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'nomCentre',
                'label' => 'Centre',
                'required' => false,
                'placeholder' => 'Sélectionnez un centre',
            ])

            // ✅ Parent
            ->add('parent', EntityType::class, [
                'class' => Parents::class,
                'choice_label' => 'nomParent',
                'label' => 'Parent',
                'required' => false,
                'placeholder' => 'Sélectionnez un parent',
            ])

            // ✅ Groupes
            ->add('groupes', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'nomGroupe',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Groupes',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
