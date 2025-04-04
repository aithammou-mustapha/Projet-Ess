<?php

namespace App\DataFixtures;

use App\Entity\Prof;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProfFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Générateur de données en français

        for ($i = 0; $i < 10; $i++) {
            $prof = new Prof();
            $prof->setNomProf($faker->lastName);
            $prof->setPrenomProf($faker->firstName);
            $prof->setEmailProf($faker->unique()->email);
            $prof->setTelProf($faker->phoneNumber);
            $prof->setDisponibilitesProf($faker->sentence);
            $prof->setMotDePasse("password"); // Il sera automatiquement hashé par le setter
            $prof->setAvatarProf($faker->imageUrl(200, 200, 'people')); // URL d'avatar aléatoire
            $prof->setDateEnregistrementProf(\DateTimeImmutable::createFromMutable($faker->dateTimeThisYear));

            // Ajouter une référence pour chaque professeur
            $this->addReference('prof_' . $i, $prof);

            $manager->persist($prof);
        }

        $manager->flush();
    }
}
