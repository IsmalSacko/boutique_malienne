<?php

namespace App\Repository;

use App\Entity\Aad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Aad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aad[]    findAll()
 * @method Aad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aad::class);
    }

    // /**
    //  * @return Aad[] Returns an array of Aad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Aad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
