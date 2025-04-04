<?php

namespace App\DataFixtures;

use App\Entity\Inscription;
use App\Entity\Parents;
use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $inscription = new Inscription();
            $inscription->setFormule($faker->randomElement(['annuelle', 'mensuelle', 'semaine']));
            $inscription->setTarif($faker->randomFloat(2, 100, 1000));
            $inscription->setCoordBancaires($faker->iban());
            $inscription->setNumContrat($faker->unique()->numerify('CT-#####'));

            // Récupérer un parent existant
            $parent = $this->getReference('parent_' . rand(0, 4), Parents::class);
            $inscription->setParent($parent);

            // ✅ Récupérer un groupe existant
            $groupe = $this->getReference('groupe_' . rand(0, 4), Groupe::class);
            $inscription->setGroupe($groupe);

            $manager->persist($inscription);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ParentsFixtures::class,
            GroupeFixtures::class, 
        ];
    }
}
