<?php

namespace App\DataFixtures;

use App\Factory\BirthdayFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Birthday extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        BirthdayFactory::createMany(5); // returns Post[]|Proxy[]

    }
}
