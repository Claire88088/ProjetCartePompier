<?php

namespace App\Repository;

use App\Entity\TypeElementAutoroute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeElementAutoroute|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeElementAutoroute|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeElementAutoroute[]    findAll()
 * @method TypeElementAutoroute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeElementAutorouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeElementAutoroute::class);
    }

    // /**
    //  * @return TypeElementAutoroute[] Returns an array of TypeElementAutoroute objects
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
    public function findOneBySomeField($value): ?TypeElementAutoroute
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
