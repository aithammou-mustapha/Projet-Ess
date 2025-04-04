<?php

namespace App\DataFixtures;

use App\Entity\Centre;
use App\Entity\Gerant;
use App\Entity\Prof;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CentreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Créer 4 centres
        for ($i = 0; $i < 4; $i++) {
            $centre = new Centre();
            $centre->setNomCentre('Centre ' . ($i + 1));
            $centre->setNbInscrits(rand(10, 100));

            // Récupérer un gérant existant
            $gerant = $this->getReference('gerant_' . rand(0, 3), Gerant::class);
            $centre->setGerant($gerant);

            // Ajouter 2 professeurs au centre
            for ($j = 0; $j < 2; $j++) {
                $prof = $this->getReference('prof_' . rand(0, 4), Prof::class);
                $centre->addProf($prof);
            }

            // Persister le centre
            $manager->persist($centre);

            // Ajouter la référence
            $this->addReference('centre_' . $i, $centre);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GerantFixtures::class,
            ProfFixtures::class,
        ];
    }
}
