<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = UserFactory::createOne(['password'=>'$2y$13$Uh7cbRSJ9acMpnrgwF5MB.cFnfJV.BsA9JFSlWARw7jK71JVqWZGK']);
        $users = UserFactory::createMany(10);
    }
}
