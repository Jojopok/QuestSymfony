<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) : void
    {
        $faker = Factory::create();


        for ($i = 0; $i < 30; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $episode = new Episode();
                $episode->setTitle($faker->sentence);
                $episode->setNumber($j + 1);
                $episode->setSynopsis($faker->paragraph);
                $episode->setSeason($this->getReference('season_' . $i));
                $manager->persist($episode);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}