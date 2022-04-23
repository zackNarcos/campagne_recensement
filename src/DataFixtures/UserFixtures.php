<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function  __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $plainPassword = 'passer';

            $user = new User();

            $pos= rand(0,2);
            $user->setNom ('AMBASSADE');
            $user->setPrenom ('GABON');
            $user->setEmail("recensement@ambassade.ga.com");
            $encoded = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($encoded);
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
            $this->addReference("User", $user);

        $manager->flush();
    }
}
