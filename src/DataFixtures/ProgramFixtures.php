<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixture::class];
    }

    const PROGRAMS = [
        'Walking Dead' => [
            'summary' => 'Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.',
            'category' => 'categorie_4',
            'country' => 'USA',
            'year' => '2010',
            'poster' => 'https://i.pinimg.com/originals/52/5d/65/525d658c25c8200ae399747142d7baaa.jpg',
            'actor' => 'actor_1'
        ],
        'Fear The Walking Dead' => [
            'summary' => 'La série se déroule au tout début de l épidémie relatée dans la série mère The Walking Dead et se passe dans la ville de Los Angeles, et non à Atlanta. Madison est conseillère dans un lycée de Los Angeles. Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia, excellente élève qui découvre les premiers émois amoureux, et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.',
            'category' => 'categorie_4',
            'country' => 'USA',
            'year' => '2015',
            'poster' => 'https://m.media-amazon.com/images/M/MV5BYWNmY2Y1NTgtYTExMS00NGUxLWIxYWQtMjU4MjNkZjZlZjQ3XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_.jpg',
            'actor' => 'actor_4'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;

        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $program->setTitle($title);
            $program->setSummary($data['summary']);
            $program->setCountry($data['country']);
            $program->setYear($data['year']);
            $program->setPoster($data['poster']);
            $program->setCategory($this->getReference($data['category']));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
            $i++;
        }

        $manager->flush();
    }
}