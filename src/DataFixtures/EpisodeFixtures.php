<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SaisonFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR');

        for ($i=1; $i<50; $i++) {
            $episode = new Episode();
            $episode->setNumber($i);
            $episode->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true));
            $episode->setSynopsis($faker->paragraph($nbSentences = 4, $variableNbSentences = true));
            $episode->setSeason($this->getReference('saison_'.$faker->numberBetween($min = 1, $max = 9)));

            $manager->persist($episode);
            $this->addReference('épisode_' . $i, $episode);
        }

        $manager->flush();
    }
}