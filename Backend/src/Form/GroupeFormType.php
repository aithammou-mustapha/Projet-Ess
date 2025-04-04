<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\Prof;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGroupe', TextType::class, [
                'label' => 'Nom du Groupe',
                'required' => false,

            ])
            ->add('typeGroupe', ChoiceType::class, [
                'label' => 'Type de Groupe',
                'choices' => [
                    'Normal' => 'normal',
                    'Stage' => 'stage',
                ],
                'placeholder' => 'Sélectionnez un type',
                'required' => false,

            ])
            ->add('avatarGroupe', FileType::class, [
                'label' => 'Avatar du Groupe',
                'required' => false,
                'mapped' => false,
                'data_class' => null,
            ])
            ->add('niveauGroupe', TextType::class, [
                'label' => 'Niveau',
                'required' => false,

            ])
            ->add('capaciteGroupe', IntegerType::class, [
                'label' => 'Capacité',
                'required' => false,

            ])
            ->add('descriptionGroupe', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de Début',
                'widget' => 'single_text',
                'required' => true,

            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de Fin',
                'widget' => 'single_text',
                'required' => true,

            ])
            ->add('heureDebut', TimeType::class, [ // Changement de TextType à TimeType
                'label' => 'Heure de Début',
                'widget' => 'single_text',
                'required' => true,

            ])
            ->add('heureFin', TimeType::class, [ // Changement de TextType à TimeType
                'label' => 'Heure de Fin',
                'widget' => 'single_text',
                'required' => true,

            ])
            ->add('matieresGroupe', TextType::class, [
                'label' => 'Matières',
                'required' => false,

            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'numSalle',
                'label' => 'Salle',
                'required' => false,

            ])
            ->add('prof', EntityType::class, [
                'class' => Prof::class,
                'choice_label' => 'nomProf',
                'label' => 'Professeur',
                'required' => false,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }

    
}
