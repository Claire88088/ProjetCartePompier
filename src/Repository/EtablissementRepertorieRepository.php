<?php

namespace App\Repository;

use App\Entity\EtablissementRepertorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtablissementRepertorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtablissementRepertorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtablissementRepertorie[]    findAll()
 * @method EtablissementRepertorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepertorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtablissementRepertorie::class);
    }

    // /**
    //  * @return EtablissementRepertorie[] Returns an array of EtablissementRepertorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EtablissementRepertorie
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
