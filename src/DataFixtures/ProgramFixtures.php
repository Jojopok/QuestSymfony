<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $program = new Program();
        $program->setTitle('Arcane');
        $program->setSynopsis('Des piou piou de LoL');
        $program->setCategory($this->getReference('category_Animation'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_0', $program);

        $program = new Program();
        $program->setTitle('Sense8');
        $program->setSynopsis('Des gens avec des pouvoirs et qui ont des gros soucis de libido');
        $program->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_1', $program);

        $program = new Program();
        $program->setTitle('The last of us');
        $program->setSynopsis('Des zombies et un daron');
        $program->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_2', $program);

        $program = new Program();
        $program->setTitle('Squid Game');
        $program->setSynopsis('Des gens qui font des jeux defant chelou');
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_3', $program);

        $program = new Program();
        $program->setTitle('One piece');
        $program->setSynopsis('Des pirates qui ne savent pas nager');
        $program->setCategory($this->getReference('category_Aventure'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_4', $program);

        $program = new Program();
        $program->setTitle('Test5');
        $program->setSynopsis('blablabla');
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_5', $program);
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }


}

