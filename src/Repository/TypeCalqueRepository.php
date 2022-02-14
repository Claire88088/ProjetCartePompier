<?php

namespace App\Repository;

use App\Entity\TypeCalque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCalque|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCalque|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCalque[]    findAll()
 * @method TypeCalque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCalqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCalque::class);
    }

    public function findAllInArray()
    {
        return $this->createQueryBuilder('tc')->getQuery()->getArrayResult();
    }

    // TODO : factoriser les reque^tes
    public function findAllElementsToShow()
    {
        $query = $this->_em->createQueryBuilder('tc');
        return $query->select('tc.id as calqueId, tc.nom as calqueNom, te.nom as typeElementNom, e.texte, e.photo, e.lien, e.dateDeb, e.dateFin, p.latitude, p.longitude, i.couleur, i.lien as lienIcone')
            ->from(TypeCalque::class, 'tc')
            ->innerJoin('App\Entity\TypeElement', 'te')
            ->where('tc.id = te.typeCalque')
            ->innerJoin('App\Entity\Element', 'e')
            ->andWhere('te.id = e.typeElement')
            ->innerJoin('App\Entity\Icone', 'i')
            ->andWhere('e.icone = i.id')
            ->innerJoin('App\Entity\Point', 'p')
            ->andWhere('e.id = p.element')
            ->getQuery()->getArrayResult();
    }


    public function findAllElementsToShowOnER()
    {
        $query = $this->_em->createQueryBuilder('tc');
        return $query->select('tc.id as calqueId, tc.nom as calqueNom, te.nom as typeElementNom, e.id as idElement, e.texte, e.photo, e.lien, e.dateDeb, e.dateFin, p.latitude, p.longitude, i.couleur, i.lien as lienIcone')
            ->from(TypeCalque::class, 'tc')
            ->innerJoin('App\Entity\TypeElement', 'te')
            ->where('tc.id = te.typeCalque')
            ->innerJoin('App\Entity\Element', 'e')
            ->andWhere('te.id = e.typeElement')
            ->innerJoin('App\Entity\Icone', 'i')
            ->andWhere('e.icone = i.id')
            ->innerJoin('App\Entity\Point', 'p')
            ->andWhere('e.id = p.element')
            ->andWhere('tc.type = :type')
            ->setParameter('type', 'ER')
            ->getQuery()->getArrayResult();
    }

    public function findAllElementsToShowOnAutoroute()
    {
        $query = $this->_em->createQueryBuilder('tc');
        return $query->select('tc.id as calqueId, tc.nom as calqueNom, te.nom as typeElementNom, e.texte, e.photo, e.lien, e.dateDeb, e.dateFin, p.latitude, p.longitude, i.couleur, i.lien as lienIcone')
            ->from(TypeCalque::class, 'tc')
            ->innerJoin('App\Entity\TypeElement', 'te')
            ->where('tc.id = te.typeCalque')
            ->innerJoin('App\Entity\Element', 'e')
            ->andWhere('te.id = e.typeElement')
            ->innerJoin('App\Entity\Icone', 'i')
            ->andWhere('e.icone = i.id')
            ->innerJoin('App\Entity\Point', 'p')
            ->andWhere('e.id = p.element')
            ->andWhere('tc.type = :type')
            ->setParameter('type', 'AUTOROUTE')
            ->getQuery()->getArrayResult();
    }

    public function findAllElementsToShowOnPI()
    {
        $query = $this->_em->createQueryBuilder('tc');
        return $query->select('tc.id as calqueId, tc.nom as calqueNom, te.nom as typeElementNom, e.texte, e.photo, e.lien, e.dateDeb, e.dateFin, p.latitude, p.longitude, i.couleur, i.lien as lienIcone')
            ->from(TypeCalque::class, 'tc')
            ->innerJoin('App\Entity\TypeElement', 'te')
            ->where('tc.id = te.typeCalque')
            ->innerJoin('App\Entity\Element', 'e')
            ->andWhere('te.id = e.typeElement')
            ->innerJoin('App\Entity\Icone', 'i')
            ->andWhere('e.icone = i.id')
            ->innerJoin('App\Entity\Point', 'p')
            ->andWhere('e.id = p.element')
            ->andWhere('tc.type = :type')
            ->setParameter('type', 'PI')
            ->getQuery()->getArrayResult();
    }

    // /**
    //  * @return TypeCalque[] Returns an array of TypeCalque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeCalque
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
