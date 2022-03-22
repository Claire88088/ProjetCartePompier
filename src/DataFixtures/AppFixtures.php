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
        $commune1->setNom('Antran')->setCodePostal('86100')->setLatitude(46.849998)->setLongitude(0.53333);
        $manager->persist($commune1);

        $commune2 = new Commune();
        $commune2->setNom('Availles-en-Châtellerault')->setCodePostal('86530')->setLatitude(46.7561922634)->setLongitude(0.569776256918);
        $manager->persist($commune2);

        $commune3 = new Commune();
        $commune3->setNom('Cenon-sur-Vienne')->setCodePostal('86530')->setLatitude(46.76667)->setLongitude(0.53333);
        $manager->persist($commune3);

        $commune5 = new Commune();
        $commune5->setNom('Colombiers')->setCodePostal('86490')->setLatitude(46.7799541393)->setLongitude(0.437112922808);
        $manager->persist($commune5);

        $commune6 = new Commune();
        $commune6->setNom('Naintré')->setCodePostal('86530')->setLatitude(46.7725487578)->setLongitude(0.494183225778);
        $manager->persist($commune6);

        $commune7 = new Commune();
        $commune7->setNom('Oyré')->setCodePostal('86220')->setLatitude(46.8626653271)->setLongitude(0.646321039276);
        $manager->persist($commune7);

        $commune8 = new Commune();
        $commune8->setNom('Scorbé-Clairvaux')->setCodePostal('86140')->setLatitude(46.8070996218)->setLongitude(0.410520780394);
        $manager->persist($commune8);

        $commune9 = new Commune();
        $commune9->setNom('Sénillé St-Sauveur')->setCodePostal('86100')->setLatitude(46.8023769572)->setLongitude(0.645222775662);
        $manager->persist($commune9);

        $commune10 = new Commune();
        $commune10->setNom('Targé')->setCodePostal('86100')->setLatitude(46.800000)->setLongitude(0.583333);
        $manager->persist($commune10);

        $commune11 = new Commune();
        $commune11->setNom('Thuré')->setCodePostal('86540')->setLatitude(46.8408988705)->setLongitude(0.455456315657);
        $manager->persist($commune11);

        $commune12 = new Commune();
        $commune12->setNom('Usseau')->setCodePostal('86230')->setLatitude(46.8867943192)->setLongitude(0.494483523463);
        $manager->persist($commune12);

        // 4 types de calque
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

        // 7 types d'éléments
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

        $typeElementEltAutoroute = new TypeElement();
        $typeElementEltAutoroute->setTypeCalque($typeCalqueAutoroute)->setNom('Element Auto')->setType('AUTRE');
        $manager->persist($typeElementEltAutoroute);

        // Icones custom
        $icone1 = new Icone();
        $icone1->setUnicode('&#xe801;')->setLien('preview-marqueur.svg')->setNom("marqueur");
        $manager->persist($icone1);

        $icone2 = new Icone();
        $icone2->setUnicode('&#xe802;')->setLien('preview-pi.svg')->setNom("pi");
        $manager->persist($icone2);

        $icone3 = new Icone();
        $icone3->setUnicode('&#xe803;')->setLien('preview-immeuble.svg')->setNom("immeuble");
        $manager->persist($icone3);

        $manager->flush();
    }
}
