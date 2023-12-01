<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create();

        for ($programNumber = 0; $programNumber < 5; $programNumber++) {
            for ($seasonNumber = 1; $seasonNumber <= 5; $seasonNumber++) {
                $season = new Season();
                $season->setNumber($seasonNumber);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));

                $season->setProgram($this->getReference('program_' . $programNumber ));

                $this->addReference('program_' . $programNumber . 'season_' . $seasonNumber, $season);
                $manager->persist($season);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}