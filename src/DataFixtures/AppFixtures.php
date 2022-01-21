<?php

namespace App\DataFixtures;

use App\Entity\Element;
use App\Entity\Point;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeCalque;
use App\Entity\TypeElement;
use App\Entity\Icone;

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

        $iconeBatiment = new Icone();
        $iconeBatiment->setLien('icons8-bâtiment-24.png');
        $manager->persist($iconeBatiment);

        $iconeEssence = new Icone();
        $iconeEssence->setLien('icons8-essence-24.png');
        $manager->persist($iconeEssence);

        $iconeGaz = new Icone();
        $iconeGaz->setLien('icons8-gaz-24.png');
        $manager->persist($iconeGaz);

        $iconeUsine = new Icone();
        $iconeUsine->setLien('icons8-usine-24.png');
        $manager->persist($iconeUsine);

        $iconeEchelle = new Icone();
        $iconeEchelle->setLien('icons8-échelle-24.png');
        $manager->persist($iconeEchelle);

        $manager->flush();

        $iconeBatiment = $manager->getRepository('App:Icone')->find($iconeBatiment->getId());
        $iconeEssence = $manager->getRepository('App:Icone')->find($iconeEssence->getId());
        $iconeGaz = $manager->getRepository('App:Icone')->find($iconeGaz->getId());
        $iconeUsine = $manager->getRepository('App:Icone')->find($iconeUsine->getId());
        $iconeEchelle = $manager->getRepository('App:Icone')->find($iconeEchelle->getId());
        
        $element1 = new Element();
        $element1->setTypeElement($manager->getRepository('App:TypeElement')->find($typeElementImmeuble->getId()))->setIcone($iconeBatiment)->setTexte('Ehpad Le Village');
        $manager->persist($element1);

        $element2 = new Element();
        $element2->setTypeElement($manager->getRepository('App:TypeElement')->find($typeElementIndustrie->getId()))->setIcone($iconeUsine)->setTexte('Industrie 1');
        $manager->persist($element2);

        $manager->flush();
    }
}
