<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('TestFirstUser');
        $user->setLastname('TestLastUser');
        $user->setEmail('test@ciel-ir.eh');
        $hashedPassword = $this->hasher->hashPassword($user, '/Ciel 64240/');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();

        // Optionnel : référence pour les tests
        $this->addReference('user_test', $user);
    }
}
