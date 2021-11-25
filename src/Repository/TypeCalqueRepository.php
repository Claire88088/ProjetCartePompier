<?php

namespace App\Repository;

use App\Entity\TypeCalque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
