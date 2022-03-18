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
        $admin->setNom('admin')->setRoles(["ROLE_ADMIN"])->setPassword('admin');
        $manager->persist($admin);

        $user = new Users();
        $user->setNom('user')->setRoles(["ROLE_USER"])->setPassword('user');
        $manager->persist($user);

        // 12 communes
        $commune4 = new Commune();
        $commune4->setNom('Châtellerault')->setCodePostal('86100')->setLatitude(46.8156700185)->setLongitude(0.552598976936);
        $manager->persist($commune4);

        $commune1 = new Commune();
        $commune1->setNom('Antran')->setCodePostal('86100')->setLatitude(46.849998 )->setLongitude(0.53333);
        $manager->persist($commune1);

        $commune2 = new Commune();
        $commune2->setNom('Availles-en-Châtellerault')->setCodePostal('86530')->setLatitude(46.7561922634)->setLongitude(0.569776256918);
        $manager->persist($commune2);

        $commune3 = new Commune();
        $commune3->setNom('Cenon-sur-Vienne')->setCodePostal('86530')->setLatitude(46.76667  )->setLongitude(0.53333);
        $manager->persist($commune3);

        $commune5 = new Commune();
        $commune5->setNom('Colombiers')->setCodePostal('86490')->setLatitude(46.7799541393 )->setLongitude(0.437112922808);
        $manager->persist($commune5);

        $commune6 = new Commune();
        $commune6->setNom('Naintré')->setCodePostal('86530')->setLatitude(46.7725487578 )->setLongitude(0.494183225778);
        $manager->persist($commune6);

        $commune7 = new Commune();
        $commune7->setNom('Oyré')->setCodePostal('86220')->setLatitude(46.8626653271)->setLongitude(0.646321039276);
        $manager->persist($commune7);

        $commune8 = new Commune();
        $commune8->setNom('Scorbé-Clairvaux')->setCodePostal('86140')->setLatitude(46.8070996218 )->setLongitude(0.410520780394);
        $manager->persist($commune8);

        $commune9 = new Commune();
        $commune9->setNom('Sénillé St-Sauveur')->setCodePostal('86100')->setLatitude(46.8023769572)->setLongitude(0.645222775662);
        $manager->persist($commune9);

        $commune10 = new Commune();
        $commune10->setNom('Targé')->setCodePostal('86100')->setLatitude(46.800000)->setLongitude(0.583333);
        $manager->persist($commune10);

        $commune11 = new Commune();
        $commune11->setNom('Thuré')->setCodePostal('86540')->setLatitude(46.8408988705 )->setLongitude(0.455456315657);
        $manager->persist($commune11);

        $commune12 = new Commune();
        $commune12->setNom('Usseau')->setCodePostal('86230')->setLatitude(46.8867943192 )->setLongitude(0.494483523463);
        $manager->persist($commune12);

        /*
        $commune1 = new Commune();
        $commune1->setNom('Buxerolles')->setCodePostal('86180')->setLatitude(46.5985617)->setLongitude(0.352332);
        $manager->persist($commune1);

        $commune2 = new Commune();
        $commune2->setNom('Poitiers')->setCodePostal('86000')->setLatitude(46.580224)->setLongitude(0.340375);
        $manager->persist($commune2);
*/


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

        // icones custom
        // standard
        $iconeCaserneSVG = new Icone();
        $iconeCaserneSVG->setUnicode('&#xe805;')->setLien('preview-caserne.svg')->setNom("caserne");
        $manager->persist($iconeCaserneSVG);

        $iconeFeuSVG = new Icone();
        $iconeFeuSVG->setUnicode('&#xe804;')->setLien('preview-feu.svg')->setNom("feu");
        $manager->persist($iconeFeuSVG);

        $iconeAppleSVG = new Icone();
        $iconeAppleSVG->setUnicode('&#xe803;')->setLien('preview-test.svg')->setNom("test");
        $manager->persist($iconeAppleSVG);

//        $iconeEpingle1 = new Icone();
//        $iconeEpingle1->setLien('icons8-epingle-de-carte-32.png');
//        $manager->persist($iconeEpingle1);
//
//        $iconeEpingle2 = new Icone();
//        $iconeEpingle2->setLien('icons8-epingle-de-carte-64.png');
//        $manager->persist($iconeEpingle2);
//
//        $iconeFlag = new Icone();
//        $iconeFlag->setLien('icons8-destination-position-flag-pointer-clue-for-maps-24.png');
//        $manager->persist($iconeFlag);
//
//        $iconeLocation = new Icone();
//        $iconeLocation->setLien('icons8-location-64.png');
//        $manager->persist($iconeLocation);
//
//        $iconeSquareMarker = new Icone();
//        $iconeSquareMarker->setLien('icons8-location-marker-24.png');
//        $manager->persist($iconeSquareMarker);
//
//        $iconeFullSquareMarker = new Icone();
//        $iconeFullSquareMarker->setLien('icons8-location-marker-24-1.png');
//        $manager->persist($iconeFullSquareMarker);
//
//        $iconePush = new Icone();
//        $iconePush->setLien('icons8-push-pin-64.png');
//        $manager->persist($iconePush);
//
//        $icone9 = new Icone();
//        $icone9->setLien('icons8-epingle-de-carte-16.png');
//        $manager->persist($icone9);
//
//        $icone22 = new Icone();
//        $icone22->setLien('icons8-pointeur-64.png');
//        $manager->persist($icone22);
//
//        // bâtiments
//        $iconeBatiment = new Icone();
//        $iconeBatiment->setLien('icons8-immeuble.png');
//        $manager->persist($iconeBatiment);
//
//        $iconeUsine = new Icone();
//        $iconeUsine->setLien('icons8-usine-64.png');
//        $manager->persist($iconeUsine);
//
//        $icone7 = new Icone();
//        $icone7->setLien('icons8-enfants-zone-securité-64.png');
//        $manager->persist($icone7);
//
//        $icone8 = new Icone();
//        $icone8->setLien('icons8-entrepôt-64.png');
//        $manager->persist($icone8);
//
//        $iconeBar = new Icone();
//        $iconeBar->setLien('icons8-bar-64.png');
//        $manager->persist($iconeBar);
//
//        $iconeCentrale = new Icone();
//        $iconeCentrale->setLien('icons8-centrale-nucléaire-64.png');
//        $manager->persist($iconeCentrale);
//
//        $icone1 = new Icone();
//        $icone1->setLien('icons8-chateau-eau-64.png');
//        $manager->persist($icone1);
//
//        $icone20 = new Icone();
//        $icone20->setLien('icons8-piscine-64.png');
//        $manager->persist($icone20);
//
//        $icone30 = new Icone();
//        $icone30->setLien('icons8-société-64.png');
//        $manager->persist($icone30);
//
//        $icone28 = new Icone();
//        $icone28->setLien('icons8-restaurant-64.png');
//        $manager->persist($icone28);
//
//        $icone40 = new Icone();
//        $icone40->setLien('icons8-école-64.png');
//        $manager->persist($icone40);
//
//        $icone41 = new Icone();
//        $icone41->setLien('icons8-église-64.png');
//        $manager->persist($icone41);
//
//
//        // dangers
//        $iconeEssence = new Icone();
//        $iconeEssence->setLien('icons8-essence-64.png');
//        $manager->persist($iconeEssence);
//
//        $iconeGaz = new Icone();
//        $iconeGaz->setLien('icons8-gaz-64.png');
//        $manager->persist($iconeGaz);
//
//        $icone4 = new Icone();
//        $icone4->setLien('icons8-danger-biologique-64.png');
//        $manager->persist($icone4);
//
//        $icone10 = new Icone();
//        $icone10->setLien('icons8-erreur-64.png');
//        $manager->persist($icone10);
//
//        $icone15 = new Icone();
//        $icone15->setLien('icons8-inflammable-64.png');
//        $manager->persist($icone15);
//
//        $icone16 = new Icone();
//        $icone16->setLien('icons8-interdit-fumer-64.png');
//        $manager->persist($icone16);
//
//        $icone17 = new Icone();
//        $icone17->setLien('icons8-interdit-plonger-64.png');
//        $manager->persist($icone17);
//
//        $icone19 = new Icone();
//        $icone19->setLien('icons8-masque-gaz-64.png');
//        $manager->persist($icone19);
//
//        $icone23 = new Icone();
//        $icone23->setLien('icons8-poison-64.png');
//        $manager->persist($icone23);
//
//        $icone27 = new Icone();
//        $icone27->setLien('icons8-radioactif-64.png');
//        $manager->persist($icone27);
//
//        $icone26 = new Icone();
//        $icone26->setLien('icons8-pétrole-industrie-64.png');
//        $manager->persist($icone26);
//
//        $icone42 = new Icone();
//        $icone42->setLien('icons8-électricité-64.png');
//        $manager->persist($icone42);
//
//        // petites installations
//        $iconeEchelle = new Icone();
//        $iconeEchelle->setLien('icons8-échelle-64.png');
//        $manager->persist($iconeEchelle);
//
//        $iconeBouche = new Icone();
//        $iconeBouche->setLien('icons8-bouche-incendie-64.png');
//        $manager->persist($iconeBouche);
//
//        $icone29 = new Icone();
//        $icone29->setLien('icons8-réservoir-stockage-64.png');
//        $manager->persist($icone29);
//
//        $icone2 = new Icone();
//        $icone2->setLien('icons8-clés-64.png');
//        $manager->persist($icone2);
//
//        $icone3 = new Icone();
//        $icone3->setLien('icons8-conduite-eau-64.png');
//        $manager->persist($icone3);
//
//        $icone11 = new Icone();
//        $icone11->setLien('icons8-extincteur-64.png');
//        $manager->persist($icone11);
//
//        $icone12 = new Icone();
//        $icone12->setLien('icons8-extincteur-mousse-64.png');
//        $manager->persist($icone12);
//
//        $icone6 = new Icone();
//        $icone6->setLien('icons8-eau-64.png');
//        $manager->persist($icone6);
//
//        $icone44 = new Icone();
//        $icone44->setLien('icons8-escaliers-64.png');
//        $manager->persist($icone44);
//
//        $icone21 = new Icone();
//        $icone21->setLien('icons8-plaque-egout-64.png');
//        $manager->persist($icone21);
//
//        $icone24 = new Icone();
//        $icone24->setLien('icons8-prise-64.png');
//        $manager->persist($icone24);
//
//        $icone32 = new Icone();
//        $icone32->setLien('icons8-station-essence-64.png');
//        $manager->persist($icone32);
//
//        $icone33 = new Icone();
//        $icone33->setLien('icons8-tuyau-incendie-64.png');
//        $manager->persist($icone33);
//
//        $icone37 = new Icone();
//        $icone37->setLien('icons8-vapeur-eau-64.png');
//        $manager->persist($icone37);
//
//        $icone38 = new Icone();
//        $icone38->setLien('icons8-verrouiller-64.png');
//        $manager->persist($icone38);
//
//        $icone39 = new Icone();
//        $icone39->setLien('icons8-voiture-64.png');
//        $manager->persist($icone39);
//
//        $icone43 = new Icone();
//        $icone43->setLien('icons8-éolienne-64.png');
//        $manager->persist($icone43);
//
//        // alarmes
//        $iconeAlarme = new Icone();
//        $iconeAlarme->setLien('icons8-alarme-incendie-64.png');
//        $manager->persist($iconeAlarme);
//
//        $iconeBouton = new Icone();
//        $iconeBouton->setLien('icons8-bouton-alarme-incendie-64.png');
//        $manager->persist($iconeBouton);
//
//        $icone5 = new Icone();
//        $icone5->setLien('icons8-douche-64.png');
//        $manager->persist($icone5);
//
//        $icone31 = new Icone();
//        $icone31->setLien('icons8-sortie-urgence-64.png');
//        $manager->persist($icone31);
//
//
//        // autoroute
//        $iconeBarriere = new Icone();
//        $iconeBarriere->setLien('icons8-barrière-64.png');
//        $manager->persist($iconeBarriere);
//
//        $icone25 = new Icone();
//        $icone25->setLien('icons8-péage-64.png');
//        $manager->persist($icone25);



        // 2 nouveaux éléments avec 2 points
        $element1 = new Element();
        $element1->setTypeElement($typeElementImmeuble)->setIcone($iconeCaserneSVG)->setTexte('Ehpad Le Village')->setNom("Ephad");
        $manager->persist($element1);

        $element2 = new Element();
        $element2->setTypeElement($typeElementIndustrie)->setIcone($iconeFeuSVG)->setTexte('Industrie 1')->setNom("Industrie");
        $manager->persist($element2);

        $element3 = new Element();
        $element3->setTypeElement($typeElementIndustrie)->setIcone($iconeAppleSVG)->setTexte('Gaz')->setNom("Gaz");
        $manager->persist($element3);

        $point1 = new Point();
        $point1->setElement($element1)->setLatitude(46.832612)->setLongitude(0.552458)->setRang(1);
        $manager->persist($point1);

        $point2 = new Point();
        $point2->setElement($element2)->setLatitude(46.812177)->setLongitude(0.554433)->setRang(1);
        $manager->persist($point2);

        $point3 = new Point();
        $point3->setElement($element3)->setLatitude(46.83)->setLongitude(0.57)->setRang(1);
        $manager->persist($point3);

        $manager->flush();
    }
}
