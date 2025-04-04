<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer des salles avec des données aléatoires
        for ($i = 0; $i < 10; $i++) {
            $salle = new Salle();
            $salle->setNumSalle($faker->word() . '-' . $faker->randomNumber(3));
            $salle->setDisponibilitesSalle($faker->sentence);
            $salle->setCapaciteSalle($faker->numberBetween(20, 50));

            // Ajouter une référence pour chaque salle
            $this->addReference('salle_' . $i, $salle);

            // Persist l'entité salle
            $manager->persist($salle);
        }

        // Sauvegarder les données
        $manager->flush();
    }
}
