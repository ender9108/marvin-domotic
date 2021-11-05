<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setFirstname('Alexandre')
            ->setLastname('Berthelot')
            ->setEmail('darkender91@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'n0c3d2l8-A'))
        ;

        $manager->persist($user);
        $manager->flush();
    }
}
