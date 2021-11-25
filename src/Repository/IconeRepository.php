<?php

namespace App\Repository;

use App\Entity\Icone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Icone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Icone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Icone[]    findAll()
 * @method Icone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IconeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Icone::class);
    }

    // /**
    //  * @return Icone[] Returns an array of Icone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Icone
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
