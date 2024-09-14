<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'utilisateur Admin
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        // Création de l'utilisateur Manager
        $managerUser = new User();
        $managerUser->setEmail('manager@gmail.com');
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setPassword($this->passwordHasher->hashPassword($managerUser, 'manager'));
        $manager->persist($managerUser);

        // Création de l'utilisateur User
        $user = new User();
        $user->setEmail('user@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));
        $manager->persist($user);

        // Enregistrement des utilisateurs en base
        $manager->flush();
    }
}
