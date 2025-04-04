<?php

namespace App\DataFixtures;

use App\Entity\Gerant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GerantFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 4; $i++) {
            $gerant = new Gerant();
            $gerant->setNomGerant($faker->lastName);
            $gerant->setPrenomGerant($faker->firstName);
            $gerant->setEmailGerant($faker->email);
            $gerant->setTelGerant($faker->phoneNumber);

            // Hachage du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($gerant, 'password123');
            $gerant->setPassword($hashedPassword);

            $gerant->setAvatarGerant('https://www.example.com/avatars/default-avatar.png');
            $gerant->setRoles(['ROLE_ADMIN']);

            $manager->persist($gerant);

            // Ajout de la référence pour l'utiliser dans d'autres fixtures
            $this->addReference('gerant_' . $i, $gerant);
        }

        $manager->flush();
    }
}
