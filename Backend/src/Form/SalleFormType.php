<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SalleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numSalle', TextType::class, [
                'label' => 'Numéro de la Salle',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: A101']
            ])
            ->add('capaciteSalle', IntegerType::class, [
                'label' => 'Capacité de la Salle',
                'required' => false,
                'attr' => ['min' => 1]
            ])
            ->add('disponibilitesSalle', TextType::class, [
                'label' => 'Disponibilités de la Salle',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: Disponible du lundi au vendredi']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
