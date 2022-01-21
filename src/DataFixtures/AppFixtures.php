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
        $typeCalqueER->setNom('Etablissements Répertoriés')->setType('ER');
        $manager->persist($typeCalqueER);

        $typeCalqueTravaux = new TypeCalque();
        $typeCalqueTravaux->setNom('Travaux')->setType('TRAVAUX');
        $manager->persist($typeCalqueTravaux);

        $typeCalqueAutoroute = new TypeCalque();
        $typeCalqueAutoroute->setNom('Autoroute')->setType('AUTOROUTE');
        $manager->persist($typeCalqueAutoroute);

        $typeCalquePI = new TypeCalque();
        $typeCalquePI->setNom('Poteaux Incendie')->setType('PI');
        $manager->persist($typeCalquePI);

        $typeCalquePiscine = new TypeCalque();
        $typeCalquePiscine->setNom('Piscines')->setType('AUTRE');
        $manager->persist($typeCalquePiscine);

        $manager->flush();

        $calqueER = $manager->getRepository('App:TypeCalque')->find($typeCalqueER->getId());
        $calqueTravaux = $manager->getRepository('App:TypeCalque')->find($typeCalqueTravaux->getId());
        $calqueAutoroute = $manager->getRepository('App:TypeCalque')->find($typeCalqueAutoroute->getId());
        $calquePI = $manager->getRepository('App:TypeCalque')->find($typeCalquePI->getId());
        //$calquePiscine = $manager->getRepository('App:TypeCalque')->find($typeCalquePiscine->getId());

        $typeElementImmeuble = new TypeElement();
        $typeElementImmeuble->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueER))->setNom('Immeuble')->setType('IMMEUBLE');
        $manager->persist($typeElementImmeuble);

        $typeElementIndustrie = new TypeElement();
        $typeElementIndustrie->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueER))->setNom('Industrie')->setType('INDUSTRIE');
        $manager->persist($typeElementIndustrie);

        $typeElementTravaux = new TypeElement();
        $typeElementTravaux->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueTravaux))->setNom('Travaux')->setType('TRAVAUX');
        $manager->persist($typeElementTravaux);

        $typeElementAcces = new TypeElement();
        $typeElementAcces->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueAutoroute))->setNom('Accès barrière de sécurité')->setType('ACCES');
        $manager->persist($typeElementAcces);

        $typeElementEchangeur = new TypeElement();
        $typeElementEchangeur->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueAutoroute))->setNom('Echangeur')->setType('ECHANGEUR');
        $manager->persist($typeElementEchangeur);

        $typeElementPKm = new TypeElement();
        $typeElementPKm->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueAutoroute))->setNom('Point kilométrique')->setType('PK');
        $manager->persist($typeElementPKm);

        $typeElementPI = new TypeElement();
        $typeElementPI->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calquePI))->setNom('Poteau incendie')->setType('PI');
        $manager->persist($typeElementPI);

        $typeElementEcoleMat = new TypeElement();
        $typeElementEcoleMat->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueER))->setNom('Ecole Maternelle')->setType('AUTRE');
        $manager->persist($typeElementEcoleMat);

        $typeElementEltAutoroute = new TypeElement();
        $typeElementEltAutoroute->setTypeCalque($manager->getRepository('App:TypeCalque')->find($calqueAutoroute))->setNom('Element Auto')->setType('AUTRE');
        $manager->persist($typeElementEltAutoroute);

        $manager->flush();
    }
}
