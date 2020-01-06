<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SaisonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for ($i=1; $i<10; $i++) {
            $season = new Season();
            $season->setNumber($i);
            $season->setYear($faker->numberBetween(2000, 2019));
            $season->setDescription($faker->paragraph($nbSentences = 4, $variableNbSentences = true));
            $season->setProgram($this->getReference('program_'.$faker->numberBetween($min = 0, $max = 1)));

            $manager->persist($season);
            $this->addReference('saison_' . $i, $season);
        }

        $manager->flush();
    }
}