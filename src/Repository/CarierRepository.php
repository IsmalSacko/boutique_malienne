<?php

namespace App\Repository;

use App\Entity\Carier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Carier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carier[]    findAll()
 * @method Carier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carier::class);
    }

    // /**
    //  * @return Carier[] Returns an array of Carier objects
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
    public function findOneBySomeField($value): ?Carier
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
