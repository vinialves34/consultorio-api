<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = new User();
        $user->setUsername('usuario')
             ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$YXc1UmRlRlJXRjlOT1FFMQ$sZi/exj160vAnafhBniqE459DHYttuosKQG37ftYkQ8');
        
        $manager->persist($user);
        $manager->flush();
    }
}
