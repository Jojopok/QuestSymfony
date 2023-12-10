<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Faker\Factory;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $manager->persist($actor);

            $programs = $manager->getRepository(Program::class)->findAll();

            // Vérifier si des programmes existent avant de tenter de les récupérer aléatoirement
            if (!empty($programs)) {
                $randomPrograms = $faker->randomElements($programs, 3);

                foreach ($randomPrograms as $program) {
                    $actor->addProgram($program);
                }
            }

            $manager->flush();
            $this->addReference("actor_$i", $actor);
        }
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class, // Ajoutez ProgramFixtures comme dépendance si nécessaire
        ];
    }
}
