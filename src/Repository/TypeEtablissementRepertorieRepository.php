<?php

namespace App\Repository;

use App\Entity\TypeEtablissementRepertorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeEtablissementRepertorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEtablissementRepertorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEtablissementRepertorie[]    findAll()
 * @method TypeEtablissementRepertorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEtablissementRepertorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEtablissementRepertorie::class);
    }

    // /**
    //  * @return TypeEtablissementRepertorie[] Returns an array of TypeEtablissementRepertorie objects
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
    public function findOneBySomeField($value): ?TypeEtablissementRepertorie
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
