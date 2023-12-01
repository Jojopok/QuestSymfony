<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) : void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 6; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $season = new Season();
                $season->setNumber($j + 1);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraph());

                $season->setProgram($this->getReference('program_' . $i));
                $this->addReference('season_' . ($i * 5 + $j), $season);
                $manager->persist($season);
            }
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}