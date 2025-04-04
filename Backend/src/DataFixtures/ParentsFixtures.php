<?php

namespace App\DataFixtures;

use App\Entity\Parents;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ParentsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Générateur de données en français

        for ($i = 0; $i < 10; $i++) {
            $parent = new Parents();
            $parent->setNomParent($faker->lastName);
            $parent->setPrenomParent($faker->firstName);
            $parent->setEmailParent($faker->email);
            $parent->setTelParent($faker->phoneNumber);
            $parent->setAdresseParent($faker->address);
            $parent->setMotDePasse(password_hash("password", PASSWORD_BCRYPT));

            $manager->persist($parent);

            // Stocker la référence pour la récupérer dans InscriptionFixtures
            $this->addReference('parent_' . $i, $parent);
        }

        $manager->flush();
    }
}
