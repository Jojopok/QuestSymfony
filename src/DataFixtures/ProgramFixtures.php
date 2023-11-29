<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const FILMS = [
        [
        'title' => 'Walkind Dead',
        'synopsis' => "Des zombies envahissent la terre",
        'category' => 'category_Action',
        ],
    [
        'title' => 'The last of us',
        'synopsis' => "Des zombies et des gens",
        'category' => 'category_Horreur',
        ],
    [
        'title' => 'Sense8',
        'synopsis' => "des gens avec des pouvoirs",
        'category' => 'category_Fantastique',
        ],
    [
        'title' => 'Arcane',
        'synopsis' => "Des personages de lol qui font piou piou",
        'category' => 'category_Animation',
        ],
    [
        'title' => 'One piece',
        'synopsis' => "Des pirates",
        'category' => 'category_Aventure',
    ],
        ];
    public function load(ObjectManager $manager)
    {
        foreach (self::FILMS as $filmData) {
            $program = new Program();
            $program->setTitle($filmData['title']);
            $program->setSynopsis($filmData['synopsis']);
            $program->setCategory($this->getReference($filmData['category']));
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }


}

