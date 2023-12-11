<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
/*$this->slugger=slug*/
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $program->setTitle('Arcane');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $program->setSynopsis('Des piou piou de LoL');
        $program->setCategory($this->getReference('category_Animation'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_0', $program);

        $program = new Program();
        $program->setTitle('Sense8');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $program->setSynopsis('Des gens avec des pouvoirs et qui ont des gros soucis de libido');
        $program->setCategory($this->getReference('category_Fantastique'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_1', $program);

        $program = new Program();
        $program->setTitle('The last of us');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $program->setSynopsis('Des zombies et un daron');
        $program->setCategory($this->getReference('category_Horreur'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_2', $program);

        $program = new Program();
        $program->setTitle('Squid Game');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $program->setSynopsis('Des gens qui font des jeux defant chelou');
        $program->setCategory($this->getReference('category_Action'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_3', $program);

        $program = new Program();
        $program->setTitle('One piece');
        $slug = $this->slugger->slug($program->getTitle());
        $program->setSlug($slug);
        $program->setSynopsis('Des pirates qui ne savent pas nager');
        $program->setCategory($this->getReference('category_Aventure'));
        $manager->persist($program);
        $manager->flush();
        $this->addReference('program_4', $program);
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }


}

