<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeCalque;
use App\Entity\TypeElement;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeCalqueER= new TypeCalque();
        $typeCalqueER->setId(1)->setNom('Etablissements Répertoriés')->setType('ER');
        $manager->persist($typeCalqueER);
        

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

        //$manager->flush();

        $typeElement1 = new TypeElement();
        $calqueERId = $typeCalqueER->getId();
        $typeElement1->setId(1)->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueERId))->setNom('Immeuble')->setType('IMMEUBLE');
        $manager->persist($typeElement1);
/*
        $typeElement2 = new TypeElement();
        $typeElement2->setId(2)->setTypeCalque(1)->setNom('Industrie')->setType('INDUSTRIE');
        $manager->persist($typeElement2);

        $typeElement3 = new TypeElement();
        $typeElement3->setId(3)->setTypeCalque(2)->setNom('Travaux')->setType('TRAVAUX');
        $manager->persist($typeElement3);

        $typeElement4 = new TypeElement();
        $typeElement4->setId(4)->setTypeCalque(3)->setNom('Accès barrière de sécurité')->setType('ACCES');
        $manager->persist($typeElement4);

        $typeElement5 = new TypeElement();
        $typeElement5->setId(5)->setTypeCalque(3)->setNom('Echangeur')->setType('ECHANGEUR');
        $manager->persist($typeElement5);

        $typeElement6 = new TypeElement();
        $typeElement6->setId(6)->setTypeCalque(3)->setNom('Point kilométrique')->setType('PK');
        $manager->persist($typeElement6);

        $typeElement7 = new TypeElement();
        $typeElement7->setId(7)->setTypeCalque(4)->setNom('Poteau incendie')->setType('PI');
        $manager->persist($typeElement7);

        $typeElement8 = new TypeElement();
        $typeElement8->setId(8)->setTypeCalque(1)->setNom('Ecole Maternelle')->setType('AUTRE');
        $manager->persist($typeElement8);

        $typeElement9 = new TypeElement();
        $typeElement9->setId(9)->setTypeCalque(3)->setNom('Element Auto')->setType('AUTRE');
        $manager->persist($typeElement9);
*/
        $manager->flush();
    }
}
