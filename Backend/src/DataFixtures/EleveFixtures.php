<?php

namespace App\DataFixtures;

use App\Entity\Eleve;
use App\Entity\Centre;
use App\Entity\Parents;
use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EleveFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $eleve = new Eleve();
            $eleve->setNomEleve($faker->lastName);
            $eleve->setPrenomEleve($faker->firstName);
            $eleve->setNiveau($faker->randomElement(['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale']));
            $eleve->setEtablissementScolaire($faker->company);
            $eleve->setTelEleve($faker->phoneNumber);

            // Récupérer un centre existant avec getReference()
            $centre = $this->getReference('centre_' . rand(0, 3), Centre::class);
            $eleve->setCentre($centre);

            // Récupérer un parent existant avec getReference()
            $parent = $this->getReference('parent_' . rand(0, 4), Parents::class);
            $eleve->setParent($parent);

            // Récupérer un groupe existant avec getReference()
            $groupe = $this->getReference('groupe_' . rand(0, 4), Groupe::class);
            $eleve->addGroupe($groupe); 

            $manager->persist($eleve);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CentreFixtures::class,
            ParentsFixtures::class,
            GroupeFixtures::class,
        ];
    }
}
