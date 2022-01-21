<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeCalque;

class TypeCalqueFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeCalque1 = new TypeCalque();
        $typeCalque1->setId(1)->setNom('Etablissements Répertoriés')->setType('ER');
        $manager->persist($typeCalque1);

        $typeCalque2 = new TypeCalque();
        $typeCalque2->setId(2)->setNom('Travaux')->setType('TRAVAUX');
        $manager->persist($typeCalque2);

        $typeCalque3 = new TypeCalque();
        $typeCalque3->setId(3)->setNom('Autoroute')->setType('AUTOROUTE');
        $manager->persist($typeCalque3);

        $typeCalque4 = new TypeCalque();
        $typeCalque4->setId(4)->setNom('Poteaux Incendie')->setType('PI');
        $manager->persist($typeCalque4);

        $typeCalque5 = new TypeCalque();
        $typeCalque5->setId(5)->setNom('Piscines')->setType('AUTRE');
        $manager->persist($typeCalque5);

        $manager->flush();
    }
}
