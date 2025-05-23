<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
     private $passwordEncoder;

     public function __construct(UserPasswordHasherInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setFirstName('Admin');
        $user->setLastName('Admin');
        $user->setAddress('Address');
        $user->setPhone('55');
        $user->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->passwordEncoder->hashPassword(
             $user,
            'admin'
        ));

        $manager->persist($user);

        $manager->flush();
    }
}
