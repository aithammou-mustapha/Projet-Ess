<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Gerant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CentreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCentre', TextType::class, [
                'label' => 'Nom du Centre',
                'required' => false

            ])
            ->add('nbInscrits', IntegerType::class, [
                'label' => 'Nombre d\'inscrits',
                'required' => false

            ])
            ->add('gerant', EntityType::class, [
                'class' => Gerant::class,
                'choice_label' => function ($gerant) {
                    return $gerant->getNomGerant() . ' ' . $gerant->getPrenomGerant();
                },
                'placeholder' => 'Sélectionnez un gérant',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Centre::class,
        ]);
    }
}
