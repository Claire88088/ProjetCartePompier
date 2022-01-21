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

        $typeElementImmeuble = new TypeElement();
        $typeElementImmeuble->setTypeCalque($typeCalqueER)->setNom('Immeuble')->setType('IMMEUBLE');
        $manager->persist($typeElementImmeuble);

        $typeElementIndustrie = new TypeElement();
        $typeElementIndustrie->setTypeCalque($typeCalqueER)->setNom('Industrie')->setType('INDUSTRIE');
        $manager->persist($typeElementIndustrie);

        $typeElementTravaux = new TypeElement();
        $typeElementTravaux->setTypeCalque($typeCalqueTravaux)->setNom('Travaux')->setType('TRAVAUX');
        $manager->persist($typeElementTravaux);

        $typeElementAcces = new TypeElement();
        $typeElementAcces->setTypeCalque($typeCalqueAutoroute)->setNom('Accès barrière de sécurité')->setType('ACCES');
        $manager->persist($typeElementAcces);

        $typeElementEchangeur = new TypeElement();
        $typeElementEchangeur->setTypeCalque($typeCalqueAutoroute)->setNom('Echangeur')->setType('ECHANGEUR');
        $manager->persist($typeElementEchangeur);

        $typeElementPKm = new TypeElement();
        $typeElementPKm->setTypeCalque($typeCalqueAutoroute)->setNom('Point kilométrique')->setType('PK');
        $manager->persist($typeElementPKm);

        $typeElementPI = new TypeElement();
        $typeElementPI->setTypeCalque($typeCalquePI)->setNom('Poteau incendie')->setType('PI');
        $manager->persist($typeElementPI);

        $typeElementEcoleMat = new TypeElement();
        $typeElementEcoleMat->setTypeCalque($typeCalqueER)->setNom('Ecole Maternelle')->setType('AUTRE');
        $manager->persist($typeElementEcoleMat);

        $typeElementEltAutoroute = new TypeElement();
        $typeElementEltAutoroute->setTypeCalque($typeCalqueAutoroute)->setNom('Element Auto')->setType('AUTRE');
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

        $element1 = new Element();
        $element1->setTypeElement($typeElementImmeuble)->setIcone($iconeBatiment)->setTexte('Ehpad Le Village');
        $manager->persist($element1);

        $element2 = new Element();
        $element2->setTypeElement($typeElementIndustrie)->setIcone($iconeUsine)->setTexte('Industrie 1');
        $manager->persist($element2);

        $manager->flush();

        $point1 = new Point();
        $point1->setElement($element1)->setLatitude(46.832612)->setLongitude(0.552458)->setRang(1);
        $manager->persist($point1);

        $point2 = new Point();
        $point2->setElement($element2)->setLatitude(46.812177)->setLongitude(0.554433)->setRang(1);
        $manager->persist($point2);

        $manager->flush();
    }
}
