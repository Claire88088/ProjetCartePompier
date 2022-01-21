<?php

namespace App\Repository;

use App\Entity\TypeElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeElement[]    findAll()
 * @method TypeElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeElement::class);
    }

    // /**
    //  * @return TypeElementFixtures[] Returns an array of TypeElementFixtures objects
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
    public function findOneBySomeField($value): ?TypeElementFixtures
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
