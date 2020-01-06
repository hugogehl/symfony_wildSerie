<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

//    CONST ACTORS = [
//        'Andrew Lincoln',
//        'Norman Reedus',
//        'Lauren Cohan',
//        'Alycia Debnam-Carey',
//        'George Brocheré',
//        'Kit Harngton',
//        'Emilia Clarke',
//        'Rose Leslie'
//    ];

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');
//        foreach (self::ACTORS as $key => $actorName) {
//            $actor = new Actor();
//            $actor->setName($actorName);
//            $actor->addProgram($this->getReference('program_'.$faker->numberBetween($min = 0, $max = 1)));
//
//            $manager->persist($actor);
//            $this->addReference('actor_' . $key, $actor);
//        }

        for ($i=1; $i<51; $i++) {
            $actor = new Actor();
            $actor->setName($faker->firstName.' '.$faker->lastName);
            $actor->addProgram($this->getReference('program_'.$faker->numberBetween($min = 0, $max = 1)));

            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
        }

        $manager->flush();
    }
}