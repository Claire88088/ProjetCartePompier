<?php

namespace App\Repository;

use App\Entity\TypeEtablissmentRepertorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeEtablissmentRepertorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEtablissmentRepertorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEtablissmentRepertorie[]    findAll()
 * @method TypeEtablissmentRepertorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEtablissmentRepertorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEtablissmentRepertorie::class);
    }

    // /**
    //  * @return TypeEtablissmentRepertorie[] Returns an array of TypeEtablissmentRepertorie objects
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
    public function findOneBySomeField($value): ?TypeEtablissmentRepertorie
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
