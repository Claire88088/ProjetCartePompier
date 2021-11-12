<?php

namespace App\Repository;

use App\Entity\Calque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Calque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calque[]    findAll()
 * @method Calque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calque::class);
    }

    // /**
    //  * @return Calque[] Returns an array of Calque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Calque
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
