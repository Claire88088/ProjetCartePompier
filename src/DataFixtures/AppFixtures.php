<?php

namespace App\DataFixtures;

use App\Entity\Commune;
use App\Entity\Element;
use App\Entity\Point;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeCalque;
use App\Entity\TypeElement;
use App\Entity\Icone;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 2 users
        $admin = new Users();
        $admin->setNom('admin')->setRoles(["ROLE_ADMIN"])->setPassword('$2y$13$42J55tM7N4R0jdqRc2/4DOrc9o0nt/7huhPRHBalH8bnO0bJ1T3D6');
        $manager->persist($admin);

        $user = new Users();
        $user->setNom('user')->setRoles(["ROLE_USER"])->setPassword('$2y$13$eD2Ou25gPvOIh4W203mOwe1IyEkN0ttPM309ZD4FfSxD0dMKwmqFW');
        $manager->persist($user);

        // communes
        $commune1 = new Commune();
        $commune1->setNom('Buxerolles')->setCodePostal('86180')->setLatitude(46.5985617)->setLongitude(0.352332);
        $manager->persist($commune1);

        $commune2 = new Commune();
        $commune2->setNom('Poitiers')->setCodePostal('86000')->setLatitude(46.580224)->setLongitude(0.340375);
        $manager->persist($commune2);

        $commune3 = new Commune();
        $commune3->setNom('Châtellerault')->setCodePostal('86100')->setLatitude(46.816487)->setLongitude(0.548146);
        $manager->persist($commune3);

        // 4 types de calque + 1 "Autre"
        $typeCalqueER = new TypeCalque();
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

        // 7 types d'éléments + 2 "Autre" sur les calques Autoroute et ER
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

        // 12 icones custom
        /*$iconeBatiment = new Icone();
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
        $manager->persist($iconeEchelle);*/

        $iconeBatiment = new Icone();
        $iconeBatiment->setLien('icons8-immeuble-64.png');
        $manager->persist($iconeBatiment);

        $iconeEssence = new Icone();
        $iconeEssence->setLien('icons8-essence-64.png');
        $manager->persist($iconeEssence);

        $iconeGaz = new Icone();
        $iconeGaz->setLien('icons8-gaz-64.png');
        $manager->persist($iconeGaz);

        $iconeUsine = new Icone();
        $iconeUsine->setLien('icons8-usine-64.png');
        $manager->persist($iconeUsine);

        $iconeEchelle = new Icone();
        $iconeEchelle->setLien('icons8-échelle-64.png');
        $manager->persist($iconeEchelle);

        $iconeFlag = new Icone();
        $iconeFlag->setLien('icons8-destination-position-flag-pointer-clue-for-maps-24.png');
        $manager->persist($iconeFlag);

        $iconeEpingle1 = new Icone();
        $iconeEpingle1->setLien('icons8-epingle-de-carte-32.png');
        $manager->persist($iconeEpingle1);

        $iconeEpingle2 = new Icone();
        $iconeEpingle2->setLien('icons8-epingle-de-carte-64.png');
        $manager->persist($iconeEpingle2);

        $iconeLocation = new Icone();
        $iconeLocation->setLien('icons8-location-64.png');
        $manager->persist($iconeLocation);

        $iconeSquareMarker = new Icone();
        $iconeSquareMarker->setLien('icons8-location-marker-24.png');
        $manager->persist($iconeSquareMarker);

        $iconeFullSquareMarker = new Icone();
        $iconeFullSquareMarker->setLien('icons8-location-marker-24-1.png');
        $manager->persist($iconeFullSquareMarker);

        $iconePush = new Icone();
        $iconePush->setLien('icons8-push-pin-64.png');
        $manager->persist($iconePush);

        // 2 nouveaux éléments avec 2 points
        $element1 = new Element();
        $element1->setTypeElement($typeElementImmeuble)->setIcone($iconeBatiment)->setTexte('Ehpad Le Village');
        $manager->persist($element1);

        $element2 = new Element();
        $element2->setTypeElement($typeElementIndustrie)->setIcone($iconeUsine)->setTexte('Industrie 1');
        $manager->persist($element2);

        $point1 = new Point();
        $point1->setElement($element1)->setLatitude(46.832612)->setLongitude(0.552458)->setRang(1);
        $manager->persist($point1);

        $point2 = new Point();
        $point2->setElement($element2)->setLatitude(46.812177)->setLongitude(0.554433)->setRang(1);
        $manager->persist($point2);

        $manager->flush();
    }
}
