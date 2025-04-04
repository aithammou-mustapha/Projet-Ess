<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\Salle;
use App\Entity\Prof;
use App\Entity\Centre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $groupe = new Groupe();
            $groupe->setNomGroupe($faker->word);
            $groupe->setTypeGroupe($faker->randomElement(['normal','stage']));
            $groupe->setAvatarGroupe($faker->imageUrl());
            $groupe->setNiveauGroupe($faker->randomElement(['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale']));
            $groupe->setCapaciteGroupe($faker->numberBetween(10, 30));
            $groupe->setDescriptionGroupe($faker->paragraph);
            $groupe->setDateDebut($faker->dateTimeThisYear);
            $groupe->setDateFin($faker->dateTimeThisYear);
            $groupe->setHeureDebut($faker->dateTimeThisMonth);
            $groupe->setHeureFin($faker->dateTimeThisMonth);
            $groupe->setMatieresGroupe($faker->words(3, true));
            $groupe->setBackgroundColor($faker->hexColor);

            // ✅ Récupérer une salle existante
            $salle = $this->getReference('salle_' . rand(0, 4), Salle::class);
            $groupe->setSalle($salle);

            // ✅ Récupérer un professeur existant
            $prof = $this->getReference('prof_' . rand(0, 4), Prof::class);
            $groupe->setProf($prof);

            // ✅ Récupérer un centre existant
            $centre = $this->getReference('centre_' . rand(0, 2), Centre::class);
            $groupe->setCentre($centre);

            $manager->persist($groupe);
            $this->addReference('groupe_' . $i, $groupe);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SalleFixtures::class,
            ProfFixtures::class,
            CentreFixtures::class, 
        ];
    }
}
