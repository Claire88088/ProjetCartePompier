<?php

namespace App\Repository;

use App\Entity\ElementER;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElementER|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementER|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementER[]    findAll()
 * @method ElementER[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementERRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementER::class);
    }

    // /**
    //  * @return ElementER[] Returns an array of ElementER objects
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
    public function findOneBySomeField($value): ?ElementER
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
