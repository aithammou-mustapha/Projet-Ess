<?php

namespace App\DataFixtures;

use App\Entity\Contrat;
use App\Entity\Centre;
use App\Entity\Parents;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ContratFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $contrat = new Contrat();
            $contrat->setDateSignature($faker->dateTimeThisDecade);
            $contrat->setConditionsContrat($faker->paragraph);

            // Récupérer un centre existant avec getReference()
            $centre = $this->getReference('centre_' . rand(0, 3), Centre::class); // Changer 4 à 3
            $contrat->setCentre($centre);

            // Récupérer un parent existant avec getReference()
            $parent = $this->getReference('parent_' . rand(0, 4), Parents::class);
            $contrat->setParent($parent);

            $manager->persist($contrat);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CentreFixtures::class, // Assure que CentreFixtures est exécuté avant
            ParentsFixtures::class, // Assure que ParentsFixtures est exécuté avant
        ];
    }
}

